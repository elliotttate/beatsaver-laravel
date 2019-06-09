import PQueue from 'p-queue'
import { basename, resolve } from 'path'
import { isMainThread, parentPort, workerData } from 'worker_threads'
import { TEMP_PATH } from './constants'
import { exec, glob, rimraf, unzip } from './file'

if (isMainThread || parentPort === null) {
  throw new Error('Cannot run on the main thread!')
}

const port = parentPort
const workerID: number = workerData.id

/**
 * Log to console
 */
const log = (m: string) => {
  port.postMessage({ type: 'log', payload: m, id: workerID })
}

// Init the local task queue
const queueSize: number = workerData.queueSize
const taskQueue = new PQueue({ autoStart: true, concurrency: queueSize })
log(`Worker started with queue size of ${queueSize}!`)

// Process incoming requests
parentPort.on('message', (songPath: string) => {
  taskQueue.add(async () => {
    await processSong(songPath)
    port.postMessage({ type: 'complete', payload: songPath, id: workerID })
  })
})

// Process a song
const processSong = async (songPath: string) => {
  const id = basename(songPath)

  // Check if already converted
  const hasMP3s = await glob(resolve(songPath, '*.mp3'))
  if (hasMP3s.length > 0) return log(`Skipping ${id}, already exists.`)

  // Check if song is deleted
  const hasZips = await glob(resolve(songPath, '*.zip'))
  if (hasZips.length === 0) return log(`Skipping ${id}, no zip found.`)

  // Unzip
  const unzipPath = resolve(TEMP_PATH, id)
  const [zipPath] = await glob(resolve(songPath, '*.zip'))
  await unzip(zipPath, { dir: unzipPath })

  // Find all .ogg files
  const oggFiles = await glob(resolve(unzipPath, '**/*.ogg'))
  const convertJobs = oggFiles.map(async oggPath => {
    // Grab old and new file paths
    const base = basename(oggPath)
    const mp3Path = resolve(songPath, base.replace(/.ogg$/, '.mp3'))

    // Process with ffmpeg
    log(`Processing ${id} - ${oggPath}`)
    await exec(
      `nice -n 16 ffmpeg -i "${oggPath}" -codec:a libmp3lame -q:a 0 "${mp3Path}"`
    )
    log(`Saved ${id} - ${mp3Path}`)
  })

  // Run all conversions
  await Promise.all(convertJobs)

  // Clean up
  await rimraf(unzipPath)
}
