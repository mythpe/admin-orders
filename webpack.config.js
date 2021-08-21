

let path = require('path');

module.exports = {
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources'),
      '@components': path.resolve(__dirname, 'resources/components'),

      '@base-inputs': path.resolve(__dirname, 'resources/components/base/inputs'),

      '@views': path.resolve(__dirname, 'resources/js/views'),
      '@errors': path.resolve(__dirname, 'resources/js/views/errors'),
      '@plugins': path.resolve(__dirname, 'resources/js/plugins'),
      '@routes': path.resolve(__dirname, 'resources/js/routes'),
      '@locales': path.resolve(__dirname, 'resources/js/locales'),
      '@helpers': path.resolve(__dirname, 'resources/js/helpers'),
      '@mixins': path.resolve(__dirname, 'resources/js/mixins'),
      '@images': path.resolve(__dirname, 'resources/dist/images'),
      '@fonts': path.resolve(__dirname, 'resources/dist/fonts')
    }
  }
};
