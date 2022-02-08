<template>
  <div class="pmonth">
    <div class="pmonth__item"
    v-for="month in salesPerMonths" :key="month">
      <p class="pmonth__header">{{month.monthName}}</p>

      <simpleTable :tableData="month.sales"></simpleTable>

      <div class="pmonth__footer">
        <p class="pmonth__footNote">Пришло: {{month.totals.totalIncome}} ₽</p>
        <p class="pmonth__footNote">Ушло: {{month.totals.totalCosts}} ₽</p>
        <p class="pmonth__footNote">Итого: {{month.totals.total}} ₽</p>
      </div>
    </div>
  </div>
</template>

<script>
import sellTable from '../components/sellTable.vue'
import simpleTable from '../components/simpleTable.vue'

export default {
  components: {sellTable, simpleTable},

  data: () => ({
    sales: null,
    salesPerMonths: [],
    suppliers: null,
    payers: null,
    services: null,
    monthNames: {
      '01': 'Январь', '02': 'Февраль', '03': 'Март', '04': 'Апрель',
      '05': 'Май', '06': 'Июнь', '07': 'Июль', '08': 'Август', '09': 'Сентябрь',
      '10': 'Октябрь', '11': 'Ноябрь', '12': 'Декабрь'
    },
    tableNames: {},
  }),

  methods: {
    createTableNames (datesArr) {
      let dateArr, year, monthName, resObj
      
      resObj = {}
      for (let date of datesArr) {
        dateArr = date.split('.')
        year = dateArr[1]
        monthName = this.monthNames[dateArr[0]]
        resObj[date] = `${monthName} ${year}`
      }

      return resObj
    },

    getMonthTotals (month) {
      let totals, priceCell 

      totals = {'totalIncome': 0, 'totalCosts': 0, 'total': 0}
      for (let row of month) {
        priceCell = row.data[0]
        priceCell = priceCell.replace('₽', '')
        priceCell = priceCell.replace(/\s+/g, '')
        priceCell = Number(priceCell)

        if (priceCell >= 0) {totals.totalIncome += priceCell}
        else {totals.totalCosts += priceCell}
      }

      totals.total = totals.totalIncome + totals.totalCosts

      totals.totalIncome = this.$formatter.withSpaces(totals.totalIncome)
      totals.totalCosts = this.$formatter.withSpaces(totals.totalCosts)
      totals.total = this.$formatter.withSpaces(totals.total)
      return totals
    },

    createValidTable (table, saleID) {
      let tempRow, payerCell, checker, dateCell,
      resTable, serviceCell, priceCell, IDcell

      resTable = []
      for (let row of table) {
        priceCell = this.$formatter.withSpaces(row.data[0])
        priceCell = `${priceCell} ₽`
        IDcell = `#${saleID}`

        checker = this.payers[row.data[1] - 1]
        if (checker) {payerCell = checker['NAME']}
        else {payerCell = 'не указан'}

        checker = this.services[row.data[3] - 1]
        if (checker) {serviceCell = checker['NAME']}
        else {serviceCell = 'не указан'}

        dateCell = row.data[2].split('-')
        dateCell = `${dateCell[2]}.${dateCell[1]}.${dateCell[0]}`
        if (dateCell == 'undefined.undefined.') {dateCell = 'не указана'}

        tempRow = {}
        tempRow.color = row.color
        tempRow.data = []

        tempRow.data.push(priceCell)
        tempRow.data.push(serviceCell)
        tempRow.data.push(dateCell)
        tempRow.data.push(payerCell)
        tempRow.data.push(IDcell)

        resTable.push(tempRow)
      }

      return resTable
    },

    createMonthsTables (salesObj, monthNames) {
      let resArr, monthObj

      resArr = []
      for (let key in salesObj) {
        monthObj = {}
        monthObj.monthName = monthNames[key]
        monthObj.sales = salesObj[key]
        monthObj.totals = this.getMonthTotals(salesObj[key])
        resArr.push(monthObj)
      }

      return resArr
    },
  },

  async created () {
    let sales, validSalesArr, jsonIncome, jsonCosts,
    validIncome, validCosts, tempDate, tableName, datesArr,
    tempSalesObj, monthNames
    
    sales = await this.$base.getTable('SALES')
    this.suppliers = await this.$base.getTable('SUPPLIERS')
    this.services = await this.$base.getTable('SERVICES')
    this.payers = await this.$base.getTable('PAYERS')

    validSalesArr = []

    for (let sale of sales) {
      jsonIncome = JSON.parse(sale.INCOME_TABLE)
      jsonCosts = JSON.parse(sale.COSTS_TABLE)

      validIncome = this.createValidTable(jsonIncome, sale.ID)
      validCosts = this.createValidTable(jsonCosts, sale.ID)

      validSalesArr.push(... validIncome)
      validSalesArr.push(... validCosts)
    }

    datesArr = []
    tempSalesObj = {}

    for (let row of validSalesArr) {
      if (row.data[2] == 'не указана') {continue}
      
      tempDate = row.data[2].split('.')
      tableName = `${tempDate[1]}.${tempDate[2]}`

      if (datesArr.includes(tableName)) {
        tempSalesObj[tableName].push(row)
      } else {
        datesArr.push(tableName)
        tempSalesObj[tableName] = []
        tempSalesObj[tableName].push(row)
      }
    }

    monthNames = this.createTableNames(datesArr)
    this.salesPerMonths = this.createMonthsTables(tempSalesObj, monthNames)
  }
}
</script>

<style>
.pmonth {
  display: flex;
  flex-wrap: wrap;
  padding: 20px; gap: 20px;
  background: #ecf0f1;
}

.pmonth__item {
  width: 600px;
  flex-grow: 1
}

.pmonth__header {
  margin: 20px 0;
  transition: .3s;
}

.pmonth__footer {
  display: flex;
  justify-content: space-between;
  gap: 20px;

  margin: 10px 0;
  background: white;
  border-radius: 5px;
  box-shadow: 2px 2px 5px rgba(0, 0, 0, .3);
  padding: 20px;
  transition: .3s;
}

.pmonth__footNote {
  padding: 0; margin: 0;
}
</style>