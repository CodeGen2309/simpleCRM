<template>
  <div class="flow">
    <div class="flow__filter">
      <span class="flow__filterText">начало периода</span>
      <input class="flow__dateInput" type="date" v-model="filterDate[0]">
      <span class="flow__filterText">конец периода</span>
      <input class="flow__dateInput" type="date" v-model="filterDate[1]">
    </div>

    <sellTable ref="flowTable"
      :suppsArr="suppliers" 
      :tableData="filterTable" :key="filterTable">
    </sellTable>

    <div class="flow__total">
      <p class="flow__totalState">Итого: {{total}}</p>
    </div>
  </div>
</template>

<script>
import sellTable from '../components/sellTable.vue'

export default {
  components: {sellTable, },
  data: () => ({
    table: null,
    filterDate: ['2022-01-22', '2022-01-22'],
    mappedData: null,
    tableJSON: null,
    suppliers: null,
    saleSteps: null,
  }),

  computed: {
    total () {
      let total = 0
      for (let row of this.mappedData) {total += Number(row.data[0])}
      return total
    },

    filterTable () {
      let rowCheck, firstDate, secondDate, rowDate, filterTable

      filterTable = []
      if (this.filterDate[0] == '') {
        this.mappedData = this.table
        return JSON.stringify(this.table)
      }

      for (let row of this.table) {
        firstDate = this.filterDate[0].split('-')
        secondDate = this.filterDate[1].split('-')
        rowDate = row.data[2].split('-')
        rowCheck = true

        if (Number(rowDate[0]) < Number(firstDate[0])
        || Number(rowDate[0]) > Number(secondDate[0]))
        {
          rowCheck = false
          console.log('FIRST IS FALSE')
        }

        if (Number(rowDate[1]) < Number(firstDate[1])
        || Number(rowDate[1]) > Number(secondDate[1]))
        {
          rowCheck = false
          console.log('SECOND IS FALSE')
        }

        if (Number(rowDate[2]) < Number(firstDate[2])
        || Number(rowDate[2]) > Number(secondDate[2]))
        {
          rowCheck = false
          console.log('THIRD IS FALSE')
        }

        if (rowCheck) {filterTable.push(row)}
      }

      this.mappedData = filterTable
      return JSON.stringify(filterTable)
    },
  },

  methods: {
    print (item) {console.log(item)},
  },

  async created () {
    let sales, saleSteps, suppliers
    let incomeTable, costsTable, resArr

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
    this.suppliers = suppliers
    this.saleSteps = saleSteps
  }
}
</script>

<style>
.flow {background: #ecf0f1}
.flow__total {margin: 20px}

.flow__filter {
  display: flex;
  gap: 20px;
  padding: 20px;
}

.flow__filterText {
  font-style: italic;
}

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