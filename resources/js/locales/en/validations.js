
const messages = import('vee-validate/dist/locale/en.json')
export default {
  code: 'en',
  messages: {
    ...messages,
    int: '{_field_} It can only contain numbers',
    mobile: '{_field_} incorrect',
  },
  names: require('./attributes').default,
  fields: {},
}
