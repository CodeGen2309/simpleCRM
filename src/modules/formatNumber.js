let formatter =  {
  withSpaces (number) {
    return new Intl.NumberFormat('ru-RU').format(number)
  },
}

export default {
  install: (app) => app.config.globalProperties.$formatter = formatter
}