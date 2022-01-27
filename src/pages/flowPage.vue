<template>
  <div class="flow">
    <dateFilter class="flow__filter"
    @dateIsChanged="applyFilter">
    </dateFilter>

    <sellTable ref="flowTable"
      :suppsArr="suppliers" 
      :tableData="tableJSON" :key="tableJSON">
    </sellTable>

    <div class="flow__total">
      <p class="flow__totalState">Доходы: {{totalIncome}}</p>
      <p class="flow__totalState">Расходы: {{totalCosts}}</p>
      <p class="flow__totalState">Итого: {{total}}</p>
    </div>
  </div>
</template>

<script>
import sellTable from '../components/sellTable.vue'
import dateFilter from '../components/dateFilter.vue'

export default {
  components: {sellTable, dateFilter},
  data: () => ({
    table: null, mappedData: null,
    tableJSON: null, suppliers: null,
    saleSteps: null,
  }),

  computed: {
    total () {
      let total = 0
      for (let row of this.mappedData) {total += Number(row.data[0])}
      return total
    },

    totalCosts () {
      let costs, cell
      costs = 0

      for (let row of this.mappedData) {
        cell = Number(row.data[0])
        if (cell < 0) {costs +=  cell}
      }

      return costs
    },

    totalIncome () {
      let costs, cell
      costs = 0

      for (let row of this.mappedData) {
        cell = Number(row.data[0])
        if (cell > 0) {costs +=  cell}
      }

      return costs
    },
  },

  methods: {
    print (item) {console.log(item)},

    applyFilter (newDate) {
      this.mappedData = []
      if (newDate[1].length == 1) {newDate[1] = '0' + newDate[1]}

      for (let row of this.table) {
        let date =  row.data[2].split('-')
        if (date[0] == newDate[0] && date[1] == newDate[1]) {
          this.mappedData.push(row)
        }

        this.tableJSON = JSON.stringify(this.mappedData)
      }
    },
  },

  async created () {
    let sales, saleSteps, suppliers,
    incomeTable, costsTable, resArr

    sales = await this.$base.getTable('SALES')
    saleSteps = await this.$base.getTable('SALESTEPS')
    suppliers = await this.$base.getTable('SUPPLIERS')
    resArr = []

    for (let item of sales) {
      incomeTable = JSON.parse(item.INCOME_TABLE)
      costsTable = JSON.parse(item.COSTS_TABLE)

      for (let row of incomeTable) {resArr.push(row)}
      for (let row of costsTable) {resArr.push(row)}
    }

    this.table = resArr
    this.tableJSON = JSON.stringify(resArr)
    this.mappedData = resArr
    this.suppliers = suppliers
    this.saleSteps = saleSteps
  }
}
</script>

<style>
.flow {background: #ecf0f1}
.flow__total {margin: 20px}
.flow__filter {padding: 20px}

.flow__dateInput {
  border:none; outline: none;
  border-bottom: 1px solid black;
  background: inherit;
}

.flow__total {
  position: fixed;
  padding: 10px;
  border-radius: 10px;
  bottom: 20px; right: 20px;
  background: white;
}
</style>