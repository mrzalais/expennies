const ajax = (url, method = 'get', data = {}) => {
  method = method.toLowerCase()

  let options = {
    method,
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    }
  }

  const csrfMethods = new Set(['post', 'put', 'delete', 'patch'])

  if (csrfMethods.has(method)) {
    options.body = JSON.stringify({...data, ...getCsrfFields()})
  } else if (method === 'get') {
    url += '?' + (new URLSearchParams(data)).toString();
  }

  return fetch(url, options).then(response => {
    if (!response.ok) {
      if (response.status === 422) {
        response.json().then(errors => {
          handleValidationErrors(errors)
        })
      }
    }

    return response
  })
}

const get  = (url, data) => ajax(url, 'get', data)
const post = (url, data) => ajax(url, 'post', data)

function handleValidationErrors(errors) {
  for (const name in errors) {
    const element = document.querySelector(`input[name="${ name }"]`)

    element.classList.add('is-invalid')
  }
}

function getCsrfFields() {
  const csrfNameField  = document.querySelector('#csrfName')
  const csrfValueField = document.querySelector('#csrfValue')
  const csrfNameKey    = csrfNameField.getAttribute('name')
  const csrfName       = csrfNameField.content
  const csrfValueKey   = csrfValueField.getAttribute('name')
  const csrfValue      = csrfValueField.content

  return {
    [csrfNameKey]: csrfName,
    [csrfValueKey]: csrfValue
  }
}

export {
  ajax,
  get,
  post
}
