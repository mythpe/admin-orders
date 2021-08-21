

const files = require.context('@plugins', true, /\index.js$/i)

files.keys().map((key) => files(key))
