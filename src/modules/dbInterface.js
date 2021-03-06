import axios from 'axios'
import urlConfig from './testURL.json'

let inter = {
  connector: axios,
  url: urlConfig.URL,

  async sendRequest (data) {
    let JSONData = JSON.stringify(data)
    let res = await this.connector.post(this.url, `data=${JSONData}`)
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

  async addSale (tableData) {
    let data = {action: 'addSale', tableData}
    let res = await this.sendRequest(data)
    return res
  },

  async deleteSale (saleID) {
    let data = {action: 'deleteSale', saleID}
    let res = await this.sendRequest(data)
    return res
  },

  async updateSale (saleData) {
    let data = {action: 'updateSale', saleData}
    let res = await this.sendRequest(data)

    console.log(saleData)
    return res
  },
}

export default {
  install: (app) => app.config.globalProperties.$base = inter
}