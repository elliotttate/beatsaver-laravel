## MP3 Converter
_Extracts and converts all stored songs into `mp3` format._  
Built with TypeScript, uses `ffmpeg`.

### Requirements
* Node v11 or later
* Yarn

### Usage
From the script's root folder:

```sh
$ yarn
$ yarn build
$ node .
```

#### Controlling concurrency
You can specify the number of worker threads with the `--workers N` option.  
This defaults to half the available CPU threads.

Each worker thread has a task queue, you can limit each threads task queue size with the `--queueSize N` option. This defaults to 1.
