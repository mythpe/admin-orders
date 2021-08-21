

import WebFontLoader from 'webfontloader'

// async load fonts
WebFontLoader.load({
  google: {
    families: [
      'Material+Icons'
    ]
  },
  custom: {
    families: [
      'Material Design Icons',
      'Font Awesome 5'
    ],
    urls: [
      'https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css',
      'https://use.fontawesome.com/releases/v5.8.1/css/all.css'
    ]
  }
})
