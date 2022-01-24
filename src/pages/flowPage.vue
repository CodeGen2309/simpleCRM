<template>
  <div class="flow">
    <input type="date" v-model="filterDate">

    <sellTable ref="flowTable"
      :suppsArr="suppliers"
      :tableData="filterTable" :key="filterTable">
    </sellTable>
  </div>

  <span class="flow__total" @click="print(filterDate)">ИТОГО: {{total}}</span>
</template>

<script>
import sellTable from '../components/sellTable.vue'

export default {
  components: {sellTable, },
  data: () => ({
    table: null,
    filterDate: '2022-01-22',
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
      let filterTable = []

      if (this.filterDate == '') {
        this.mappedData = this.table
        return JSON.stringify(this.table)
      }

      for (let row of this.table) {
        if (row.data[2] == this.filterDate) {filterTable.push(row)}
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
.flow {margin: 20px}
.flow__total {margin: 40px}
</style>