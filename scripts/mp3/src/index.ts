#!/usr/bin/env node
import { cpus } from 'os'
import { join } from 'path'
import { Worker } from 'worker_threads'
import { argv } from 'yargs'
import { SONGS_PATH } from './constants'
import { glob } from './file'
import { log } from './log'

const workers = (argv.w ||
  argv.workers ||
  Math.floor(cpus().length / 2)) as number
const queueSize = (argv.q || argv.queueSize || 1) as number

// Define worker pool
const pool = {
  assign: () => {
    if (pool.lastUsed === -1) {
      pool.lastUsed = 0
      return pool.workers[0]
    }

    const incremented = pool.lastUsed + 1
    const next = incremented > pool.workers.length - 1 ? 0 : incremented

    pool.lastUsed = next
    return pool.workers[next]
  },
  lastUsed: -1,
  workers: Array.from(new Array(workers)).map(
    (_, i) =>
      new Worker(join(__dirname, 'worker.js'), {
        workerData: { id: i, queueSize },
      })
  ),
}

// Init a task queue
// Used to know when we're done
const taskQueue: Set<string> = new Set()

// Read songs from BeatSaver and queue jobs for each
const readSongs = async () => {
  const basePath = join(SONGS_PATH)
  const songPaths = await glob(basePath)

  for (const songPath of songPaths) {
    taskQueue.add(songPath)

    const w = pool.assign()
    w.postMessage(songPath)
  }
}

interface IResponse {
  type: 'log' | 'complete'
  payload: string
  id: number
}

// Attach event listeners to each worker in the pool
pool.workers.forEach(w => {
  w.on('message', (resp: IResponse) => {
    if (resp.type === 'complete') {
      taskQueue.delete(resp.payload)
      if (taskQueue.size === 0) {
        log('Processing complete!')
        process.exit(0)
      }
    } else if (resp.type === 'log') {
      log(resp.payload, resp.id)
    }
  })
})

log('Starting processing!')
readSongs()
