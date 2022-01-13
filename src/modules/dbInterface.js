import axios from 'axios'

let inter = {
  connector: axios,

  async sendRequest (data) {
    let JSONData = JSON.stringify(data)
    let res = await this.connector.post('https://gergewebdev.ru/dbinter.php', `data=${JSONData}`)

    return res.data
  },

  async getTable (tableName) {
    let data = {action: 'getTable', tableName}
    let res = await this.sendRequest(data)
    return res
  },

  async showTables () {
    let data = {action: 'showTables'}
    let res = await this.sendRequest(data)
    return res
  },

  async rewriteTable (tableName, cells) {
    let data = {action: 'rewriteTable', tableName, cells}
    let res = await this.sendRequest(data)
    return res
  },
}

export default {
  install: (app) => app.config.globalProperties.$base = inter
}