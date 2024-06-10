function submitFilterForm () {
  const url = '/filters/'
  const form = $(this).parents('form');
  const formData = form.serializeArray()
  let newUrl = ''

  formData.forEach(function (item) {
    if (item.value.length > 0) {
      item.value = item.value.replace(/ /g, '+')

      if (item.value.includes(":")) {
        item.value = "range-" + item.value.replace(":", "-")
      }

      newUrl = `${newUrl}${encodeURI(item.value)}/`
    }
  })

  if (newUrl.length > 0) {
    newUrl = `${url}${newUrl}`
    form.attr('action', newUrl)
  }

  form.submit();
}
