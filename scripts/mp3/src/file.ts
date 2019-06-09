import { exec as ex } from 'child_process'
import { default as uz } from 'extract-zip'
import { default as gb } from 'glob'
import { default as rm } from 'rimraf'
import { promisify } from 'util'

export const exec = promisify(ex)
export const glob = promisify(gb)
export const rimraf = promisify(rm)
export const unzip = promisify(uz)

export default {
  exec,
  glob,
  rimraf,
  unzip,
}
