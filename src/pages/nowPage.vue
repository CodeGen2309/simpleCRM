<template>
  <div class="nowPage">
    <cardList v-for="(sale, saleIndex) in sales"
      class="nowPage__step" :listIndex="saleIndex"
      :listName="salesteps[saleIndex]" @clickOnSale="transferSale"
      :listData="sale" :key="sale">
    </cardList>    
  </div>
</template>

<script>
import draggable from 'vue3-draggable'
import cardList from '../components/cardDragList.vue'

export default {
  components: {draggable, cardList},
  emits: ['clickOnSale',],

  data: () => ({
    updateCount: 0,
    salesteps: [],
    sales: [],
  }),

  methods: {
    transferSale (sale) {
      this.$emit('clickOnSale', sale)
    }
  },

  async created () {
    let stepsDB = await this.$base.getTable('SALESTEPS')
    let salesDB = await this.$base.getTable('SALES')
    let salesteps = []
    let salesFilter = []

    stepsDB.forEach(item => {salesteps.push(item.NAME)})

    stepsDB.forEach(step => {
      salesFilter.push([])

      salesDB.forEach(sale => {
        if (step.ID == sale.STATUS_ID) {
          salesFilter[step.ID - 1].push(sale)
        }
      })
    })

    this.salesteps = salesteps
    this.sales = salesFilter
  },
}
</script>

<style>
  .nowPage {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;

    padding: 20px;
    background: #ecf0f1;
  }

  .nowPage__step {max-width: 250px}
</style>