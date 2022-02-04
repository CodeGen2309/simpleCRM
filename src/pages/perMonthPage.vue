<template>
  <div class="pmonth">
    <div class="pmonth__item"
    v-for="month in salesPerMonths" :key="month">
      <p class="pmonth__header">{{tableNames[month.checkDate]}}</p>

      <sellTable :suppsArr="payers" :forWhatArr="services"
      :tableData="JSON.stringify(month.tableData)">
      </sellTable>

      <div class="pmonth__footer">
        <p class="pmonth__footNote">Пришло: {{totals[month.checkDate]['totalIncome']}} ₽</p>
        <p class="pmonth__footNote">Ушло: {{totals[month.checkDate]['totalCosts']}} ₽</p>
        <p class="pmonth__footNote">Итого: {{totals[month.checkDate]['total']}} ₽</p>
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
    temp: [],
    monthNames: {
      '01': 'Январь', '02': 'Февраль', '03': 'Март', '04': 'Апрель',
      '05': 'Май', '06': 'Июнь', '07': 'Июль', '08': 'Август', '09': 'Сентябрь',
      '10': 'Октябрь', '11': 'Ноябрь', '12': 'Декабрь'
    },
    tableNames: {},
    tempMonth: null,
  }),

  computed: {
    totals () {
      let totals = {}

      for (let month of this.salesPerMonths) {
        let resArr = {'totalIncome': 0, 'totalCosts': 0, 'total': 0}
        let cellsArr = []

        month.tableData.forEach(item => {cellsArr.push(item.data[0])})
        cellsArr.forEach(item => {
          if (Number(item) >= 0) {resArr.totalIncome += Number(item)}
          else {resArr.totalCosts += Number(item)}
        })

        resArr.total = resArr.totalIncome + resArr.totalCosts
        totals[month.checkDate] = resArr
      }

      return totals
    }
  },

  methods: {
    fetchSale (sale, saleID) {
      let income = JSON.parse(sale.INCOME_TABLE)
      let costs = JSON.parse(sale.COSTS_TABLE)
      let resArr = []

      for (let row of income) {resArr.push(row)}
      for (let row of costs) {resArr.push(row)}
      for (let row of resArr) {row.saleID = saleID}
      return resArr
    },

    createTableNames () {
      for (let month of this.salesPerMonths) {
        let dateArr = month.checkDate.split('-')
        let monthName = this.monthNames[dateArr[1]]
        let resString = `${monthName} ${dateArr[0]}`

        this.tableNames[month.checkDate] = resString
      }
    },

    createValidTable (table) {
      let tempRow, payerCell, checker, dateCell,
      resTable, serviceCell, priceCell, IDcell

      resTable = []
      for (let row of table) {
        priceCell = this.$formatter.withSpaces(row.data[0])
        priceCell = `${priceCell} ₽`
        IDcell = `#${row.saleID}`

        checker = this.payers[row.data[1] - 1]
        if (checker) {payerCell = checker['NAME']}
        else {payerCell = 'не указан'}

        checker = this.services[row.data[3] - 1]
        if (checker) {serviceCell = checker['NAME']}
        else {serviceCell = 'не указан'}

        dateCell = row.data[2].split('-')
        dateCell = `${dateCell[2]}.${dateCell[1]}.${dateCell[0]}`

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
      let saleRows = this.fetchSale(item, item.ID)
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

    for (let month of perMonthsArr) {
      // console.log(month.tableData)
    }


    this.salesPerMonths = perMonthsArr
    this.suppliers = suppliers
    this.services = services
    this.payers = payers
    this.tempMonth = this.createValidTable(perMonthsArr[0]['tableData'])
    this.createTableNames()

    console.log(this)
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

.pmonth__item {flex-grow: 1}
.pmonth__header {
  padding: 5px 20px;
  margin: 0;
  transition: .3s;
}

.pmonth__footer {
  display: flex;
  justify-content: space-between;
  gap: 20px;

  background: white;
  border-radius: 5px;
  margin: 10px 0 0 20px;
  box-shadow: 2px 2px 5px rgba(0, 0, 0, .3);
  padding: 20px;
}

.pmonth__footNote {
  padding: 0; margin: 0;
}
</style>