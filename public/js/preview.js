/**
 * @typedef {Object} SongInfo
 * @property {string} songName
 * @property {string} songSubName
 * @property {string} authorName
 * @property {number} beatsPerMinute
 * @property {number} previewStartTime
 * @property {number} previewDuration
 * @property {{ difficulty: string, difficultyRank: number, audioPath: string, jsonPath: string }[]} difficultyLevels
 */

class PreviewPlayer {
  constructor (volume) {
    this.audio = new Audio()
    this.audio.volume = volume || 0.5

    this.onEnd = () => {}
    this.audio.addEventListener('ended', ev => {
      this.onEnd(ev)
    })
  }

  /**
   * @param {string} url URL
   * @returns {Promise.<Blob>}
   */
  static async _fetchBlob (url) {
    const resp = await fetch(url)
    return resp.blob()
  }

  /**
   * @param {Blob} blob Blob
   * @returns {Promise.<zipReader>}
   */
  static _createReader (blob) {
    return new Promise((resolve, reject) => {
      zip.createReader(
        new zip.BlobReader(blob),
        zipReader => { resolve(zipReader) }
      )
    })
  }

  /**
   * @param {zipReader} zipReader Zip Reader
   * @returns {Promise.<SongInfo>}
   */
  static _getSongInfo (zipReader) {
    return new Promise((resolve, reject) => {
      zipReader.getEntries(entries => {
        const infoJSON = entries.find(x => x.filename.includes('info.json'))

        infoJSON.getData(new zip.TextWriter(), text => {
          const json = JSON.parse(text)
          resolve(json)
        })
      })
    })
  }

  /**
   * @param {zipReader} zipReader Zip Reader
   * @param {string} audioPath Audio Path
   * @returns {Promise.<Blob>}
   */
  static _getSongBlob (zipReader, audioPath) {
    return new Promise((resolve, reject) => {
      zipReader.getEntries(entries => {
        const song = entries.find(x => x.filename.includes(audioPath))

        song.getData(
          new zip.BlobWriter(),
          blob => { resolve(blob) }
        )
      })
    })
  }

  get playing () { return !this.audio.paused }
  stop () { this.audio.pause() }

  /**
   * @param {string} url Song Zip URL
   * @returns {Promise.<SongInfo>}
   */
  async play (url) {
    const blob = await PreviewPlayer._fetchBlob(url)
    const zipReader = await PreviewPlayer._createReader(blob)

    const info = await PreviewPlayer._getSongInfo(zipReader)
    const audioPath = info.difficultyLevels[0].audioPath

    const songBlob = await PreviewPlayer._getSongBlob(zipReader, audioPath)
    const song = URL.createObjectURL(songBlob)

    this.audio.pause()
    this.audio.src = song
    this.audio.play()

    return info
  }
}
