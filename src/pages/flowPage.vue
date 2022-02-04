<template>
  <div class="flow">
    <dateFilter class="flow__filter"
    @dateIsChanged="applyFilter">
    </dateFilter>

    <simpleTable class="flow__table" :tableData="mappedData"></simpleTable>

    <div class="flow__total">
      <p class="flow__totalState">Пришло: {{formatToPrice(totalIncome)}} ₽</p>
      <p class="flow__totalState">Ушло: {{formatToPrice(totalCost)}} ₽</p>
      <p class="flow__totalState">Итого: {{formatToPrice(total)}} ₽</p>
    </div>
  </div>
</template>

<script>
import sellTable from '../components/sellTable.vue'
import dateFilter from '../components/dateFilter.vue'
import simpleTable from '../components/simpleTable.vue'

export default {
  components: {sellTable, dateFilter, simpleTable},
  data: () => ({
    table: null, mappedData: null, tableJSON: null,
    suppliers: null, saleSteps: null, services: null,
    payers: null,
    totalIncome: 0,
    totalCost: 0,
    total: 0,
  }),

  computed: {},
  methods: {
    print (item) {console.log(item)},

    applyFilter (newDate) {
      let priceCell, servCell, dateCell, payerCell, IDCell,
      currDate, formatRow, checker

      this.mappedData = []
      this.totalIncome = 0
      this.totalCost = 0

      if (!newDate) {newDate = ['2022', '1']}
      if (newDate[1].length == 1) {newDate[1] = '0' + newDate[1]}


      for (let row of this.table) {
        currDate = row.data[2].split('-')
        checker = currDate[0] == newDate[0] && currDate[1] == newDate[1]

        if (row.data[0]) {dateCell = `${currDate[2]}.${currDate[1]}.${currDate[0]}`}
        else {dateCell = 'не указана'}


        if (!this.services[row.data[3]]) {servCell = 'не указан'}
        else {servCell = this.services[row.data[3] - 1]['NAME']}

        if (!this.services[row.data[1]]) {payerCell = 'не указан'}
        else {payerCell = this.payers[row.data[1] - 1]['NAME']}

        priceCell = this.$formatter.withSpaces(row.data[0])
        priceCell = `${priceCell} ₽`

        IDCell = `№ ${row.saleID}`

        formatRow = {}
        formatRow.color = row.color
        formatRow.data = []

        formatRow.data.push(priceCell)
        formatRow.data.push(servCell)
        formatRow.data.push(dateCell)
        formatRow.data.push(payerCell)
        formatRow.data.push(IDCell)

        if (checker) {
          this.mappedData.push(formatRow)

          if (Number(row.data[0]) > 0 ) {this.totalIncome += Number(row.data[0])}
          else {this.totalCost += Number(row.data[0])}

        }

        this.total = this.totalIncome + this.totalCost
      }
    },

    formatToPrice (number) {
      return this.$formatter.withSpaces(number)
    },

  },

  async created () {
    let sales, saleSteps, suppliers, services,
    incomeTable, costsTable, resArr, payers

    sales = await this.$base.getTable('SALES')
    saleSteps = await this.$base.getTable('SALESTEPS')
    suppliers = await this.$base.getTable('SUPPLIERS')
    services = await this.$base.getTable('SERVICES')
    payers = await this.$base.getTable('PAYERS')
    resArr = []

    for (let item of sales) {
      incomeTable = JSON.parse(item.INCOME_TABLE)
      costsTable = JSON.parse(item.COSTS_TABLE)

      for (let row of incomeTable) {
        row.saleID = item.ID
        resArr.push(row)
      }

      for (let row of costsTable) {
        row.saleID = item.ID
        resArr.push(row)
      }
    }

    this.table = resArr
    this.mappedData = resArr
    this.tableJSON = JSON.stringify(resArr)
    this.suppliers = suppliers
    this.saleSteps = saleSteps
    this.services = services
    this.payers = payers
    this.applyFilter()
  }
}
</script>

<style>
.flow {
  position: relative;
  padding: 20px;
  background: #ecf0f1;
}
.flow__filter {
  margin-bottom: 20px;
}
.flow__table {
  width: 800px;
}
.flow__total {
  position: absolute;
  top: 0; right: 0;
  margin: 30px;

  background: white;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 2px 2px 3px rgba(0, 0, 0, .2);
}
.flow__totalState {
}</style>