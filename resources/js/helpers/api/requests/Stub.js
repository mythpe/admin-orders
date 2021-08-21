import axios from 'axios'

export const appendArray = (form_data, values, name) => {
  if (values instanceof File) {
    form_data.append(name, values, values.name)
  } else if (typeof values == 'object') {
    for (let key in values) {
      if (typeof values[key] === 'object') {
        let k
        if (name) {
          k = name + '[' + key + ']'
        } else {
          k = key
        }

        appendArray(form_data, values[key], k)
      } else {
        if (values[key] === true) {
          values[key] = 1
        }
        if (values[key] === false) {
          values[key] = 0
        }
        if (name) {
          form_data.append(name + '[' + key + ']', values[key])
        } else {
          // console.log(values)
          form_data.append(key, values[key])
        }
      }
    }
  }
  return form_data
}

export const Stub = prefix => {
  const url = p => prefix + (p ? `/${p}` : '')
  return {
    index (params, config = {}) {
      let u = url()
      return params === true ? u : axios.get(u, { ...config, params })
    },
    store (data = {}, config) {
      const u = url(), formData = new FormData
      // console.log(data)
      appendArray(formData, data)
      return data === true ? u : axios.post(u, formData, config)
      // return data === true ? u : axios.post(u, data, config)
    },
    show (id, ...args) {
      let u = url(id)
      return id === true ? u : axios.get(u, ...args)
    },
    update (id, data = {}, config = {}) {
      const u = url(id), formData = new FormData
      formData.append('_method', 'put')
      appendArray(formData, data)
      return id === true ? u : axios.post(u, formData, config)
      // return id === true ? u : axios.put(u, ...args)
    },
    destroy (id, ...args) {
      let u = url(id)
      return id === true ? u : axios.delete(u, ...args)
    },
    resource (params, config = {}) {
      let u = url('All')
      return params === true ? u : axios.get(u, { ...config, params })
    },
    activeOnly (params, config = {}) {
      let u = url('Resource')
      return params === true ? u : axios.get(u, { ...config, params })
    }
  }
}
