const oneClick = async (elem, key) => {
  if (!localStorage.getItem('oneclick-prompt')) {
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
