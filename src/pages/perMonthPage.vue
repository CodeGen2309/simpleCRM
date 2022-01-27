<template>
  <div class="pmonth">
    <p>THIS IS PER MONTH PAGE</p>
    <pre>{{temp}}</pre>
  </div>
</template>

<script>
import sellTable from '../components/sellTable.vue'

export default {
  components: {sellTable, },
  data: () => ({
    sales: null,
    salesPerMonths: [],
    temp: [],
    monthNames: {
      '01': 'январь',
      '02': 'февраль',
      '03': 'март',
      '04': 'апрель',
      '05': 'май',
      '06': 'июнь',
      '07': 'июль',
      '08': 'авгут',
      '09': 'сентябрь',
      '10': 'октябрь',
      '11': 'ноябрь',
      '12': 'декабрь',
    },
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
  },


  async created () {
    let sales, resTable, perMonthsArr, monthNames

    sales = await this.$base.getTable('SALES')
    resTable = []
    perMonthsArr = {}
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
      if (monthExist) {perMonthsArr[checkDate].push(row)}
      else {
        monthNames.push(checkDate)
        perMonthsArr[checkDate] = []
        perMonthsArr[checkDate].push(row)
      }
    }

    this.temp = perMonthsArr
    this.salesPerMonths = perMonthsArr
  }
}
</script>

<style>
.pmonth {
  overflow: auto;
  padding: 20px;
}
</style>