const oneClick = async (elem, key) => {
  const lastCheck = localStorage.getItem('oneclick-prompt')
  const prompt = lastCheck === undefined ||
    new Date(lastCheck).getTime() + (1000 * 60 * 60 * 24 * 31) < new Date().getTime()

  if (prompt) {
    localStorage.setItem('oneclick-prompt', new Date())

    const resp = await swal({
      icon: 'warning',
      buttons: {
        install: { text: 'Get Mod Assistant', closeModal: false, className: 'swal-button--cancel' },
        done: { text: 'OK' },
      },
      text: 'BeatSaver OneClick Install requires Mod Assistant to function.\nPlease download it and enable OneClick Install for BeatSaver on the Options page before proceeding.',
    })

    if (resp === 'install') window.open('https://github.com/Assistant/ModAssistant/releases/latest')
  }

  window.location = `beatsaver://${key}`
}
