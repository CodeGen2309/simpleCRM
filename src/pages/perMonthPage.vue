<template>
  <div class="pmonth">
    <div class="pmonth__item"
    v-for="month in salesPerMonths" :key="month">
      <p>{{tableNames[month.checkDate]}}</p>

      <sellTable :suppsArr="payers" :forWhatArr="services"
      :tableData="JSON.stringify(month.tableData)">
      </sellTable>
    </div>
  </div>
</template>

<script>
import sellTable from '../components/sellTable.vue'

export default {
  components: {sellTable, },
  data: () => ({
    sales: null,
    salesPerMonths: [],
    suppliers: null,
    payers: null,
    services: null,
    temp: [],
    monthNames: {
      '01': 'Январь', '02': 'Февраль', '03': 'Март', '04': 'Апрель',
      '05': 'Май', '06': 'Июнь', '07': 'Июль', '08': 'Август', '09': 'Сентябрь',
      '10': 'Октябрь', '11': 'Ноябрь', '12': 'Декабрь'
    },
    tableNames: {},
  }),

  methods: {
    fetchSale (sale) {
      let income = JSON.parse(sale.INCOME_TABLE)
      let costs = JSON.parse(sale.COSTS_TABLE)
      let resArr = []

      for (let row of income) {resArr.push(row)}
      for (let row of costs) {resArr.push(row)}
      return resArr
    },

    createTableNames () {
      for (let month of this.salesPerMonths) {
        let dateArr = month.checkDate.split('-')
        let monthName = this.monthNames[dateArr[1]]
        let resString = `${monthName} ${dateArr[0]}`

        this.tableNames[month.checkDate] = resString
      }
    }
  },


  async created () {
    let sales, resTable, perMonthsArr, monthNames,
    suppliers, services, payers

    sales = await this.$base.getTable('SALES')
    suppliers = await this.$base.getTable('SUPPLIERS')
    services = await this.$base.getTable('SERVICES')
    payers = await this.$base.getTable('PAYERS')

    resTable = []
    perMonthsArr = []
    monthNames = []

    for (let item of sales) {
      let saleRows = this.fetchSale(item)
      resTable.push(...saleRows)
    }

    for (let row of resTable) {
      let date = row.data[2].split('-')
      let checkDate = `${date[0]}-${date[1]}`
      let monthExist = monthNames.includes(checkDate)

      if (date[0] == '') {continue}
      if (monthExist) {
        for (let monthItem of perMonthsArr) {
          if (monthItem.checkDate == checkDate) {
            monthItem.tableData.push(row)
          }
        }
      }else {
        monthNames.push(checkDate)
        perMonthsArr.push({checkDate, tableData: []})

        for (let monthItem of perMonthsArr) {
          if (monthItem.checkDate == checkDate) {
            monthItem.tableData.push(row)
          }
        }
      }
    }

    this.salesPerMonths = perMonthsArr
    this.suppliers = suppliers
    this.services = services
    this.payers = payers
    this.createTableNames()
  }
}
</script>

<style>
.pmonth {
  display: flex;
  flex-wrap: wrap;
  padding: 20px; gap: 20px;
}
.pmonth__item {
  flex-grow: 1;
}
</style>