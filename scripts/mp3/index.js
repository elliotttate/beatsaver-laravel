#!/usr/bin/env node

const ffmpeg = require('fluent-ffmpeg');
const fs = require('fs');
const glob = require('glob');
const path = require('path');
const PQueue = require('p-queue');
const unzip = require('extract-zip');

const pResolve = path.resolve;

const queue = new PQueue({autoStart: true, concurrency: 4});
const songs = glob.sync('../../storage/app/public/songs/*');
const TMP = pResolve(process.cwd(), './tmp');

songs.forEach(songPath => {
  const id = path.basename(songPath);

  queue.add(() => new Promise((resolve, reject) => {
    // Check if already converted.
    if (glob.sync(pResolve(songPath, '*.mp3')).length) {
      log(id, 'Skipping (already exists).');
      resolve();
      return;
    }

    // Check if song is deleted.
    if (!glob.sync(pResolve(songPath, '*.zip')).length) {
      log(id, 'Skipping (no ZIP found).');
      resolve();
      return;
    }

    // Unzip.
    const unzipPath = pResolve(TMP, id);
    unzip(glob.sync(pResolve(songPath, '*.zip'))[0], {dir: pResolve(TMP, id)}, err => {
      if (err) {
        log(id, `${err}`);
        reject();
        return;
      }

      // log(id, `Unzipped to ${unzipPath}`);
      glob.sync(pResolve(unzipPath, '*.ogg')).forEach(oggPath => {
        // Store in song directory as MP3.
        const mp3Path = pResolve(
          songPath,
          path.basename(oggPath).replace(/.ogg$/, '.mp3'));

        // Convert to MP3.
        ffmpeg(oggPath)
          .output(mp3Path)
          .on('end', () => {
            // Delete temporary unzip path.
            fs.unlink(unzipPath, () => {});
            log(id, `Success! ${queue.size} remaining.`);
            resolve();
          }).on('error', err => {
            log(id, `Error converting ${e.msg}`);
            reject();
          }).run();
      });
    });
  }));
});

// Wait for finish.
queue.onIdle().then(() => {
  console.log('Finished!');
}).catch(console.error);

function log (id, msg) {
  console.log(`[${id}] ${msg}`);
}
