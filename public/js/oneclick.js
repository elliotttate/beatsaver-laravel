const oneClick = async (elem, key) => {
  const lastCheck = localStorage.getItem('oneclick-prompt')
  const prompt = lastCheck === undefined ||
    new Date(lastCheck).getTime() + (1000 * 60 * 60 * 24 * 31) < new Date().getTime()

  if (prompt) {
    localStorage.setItem('oneclick-prompt', new Date())

    const resp = await swal({
      icon: 'warning',
      buttons: {
        install: { text: 'Get ModSaber Installer', closeModal: false, className: 'swal-button--cancel' },
        done: { text: 'OK' },
      },
      text: 'OneClick Install requires ModSaber Installer to function.\nPlease install it before proceeding.',
    })

    if (resp === 'install') window.open('https://github.com/lolPants/modsaber-installer/releases')
  }

  window.location = `modsaber://song/${key}`
}
