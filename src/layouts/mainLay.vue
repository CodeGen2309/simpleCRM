<template>
  <section class="defLay">
    <head-nav class="defLay__header" :items="menuItems" @addClick = 'openCard'>
    </head-nav>

    <div class="defLay__content">
      <router-view class="defLay__view" v-slot='{Component}'>
        <transition name="backAnim"><component :is="Component" /></transition>
      </router-view>
    </div>


    <transition name = 'backAnim'>
      <div v-if="cardIsOpened" class="sideCard__back" @click="openCard"></div>
    </transition>

    <sideCard ref='sideCard' :cardData="cardData"
      :steps="salesteps" :suppliers="supps">
    </sideCard>
  </section>
</template>



<script>
import headNav from '../components/headNav.vue'
import sellForm from '../components/sellForm.vue'
import sellTable from '../components/sellTable.vue'
import sideCard from '../components/sidecard.vue'

export default {
  components: { headNav, sellForm, sellTable, sideCard },
  emits: [],
  data: () => ({
    sales: [],
    salesteps: [],
    cardData: 'NULL',
    cardIsOpened: false,
    menuItems: [
      {id: 0, name: 'Сейчас', link: '/'},
      {id: 1, name: 'Поток', link: '/flow'},
      {id: 2, name: 'Дебиторка', link: '/buh'},
      {id: 3, name: 'Логистика', link: '/logistic'},
      {id: 4, name: 'По месяцам', link: '/month'},
      {id: 5, name: 'По поставщикам', link: '/sellers'},
      {id: 6, name: 'Объем', link: '/value'},
    ],
  }),

  methods: {
    openCard () {
      this.cardIsOpened = !this.cardIsOpened
      this.$refs.sideCard.toggleCard()
    },

    getCosts () {
      let incomeTable = this.$refs.incomeTable.getTableData()
      let costTable = this.$refs.costTable.getTableData()
      let totalIncome = 0
      let totalCost = 0

      for (let row of incomeTable) {
        totalIncome += Number(row[0])
      }

      for (let row of costTable) {
        totalCost += Number(row[0])
      }

      this.totalIncome = totalIncome
      this.totalCost = totalCost
    },

    async getSales () {
      let res = await this.$base.getTable('SALES')
      return res
    },

    async getSaleSteps () {
      let res = await this.$base.getTable('SALESTEPS')
      return res
    },

    async getSupps () {
      let res = await this.$base.getTable('SUPPLIERS')
      return res
    },
  },

  async created () {
    let sales = await this.getSales()
    let steps = await this.getSaleSteps()
    let supps = await this.getSupps()

    sales.forEach(item => this.sales.push(item))
    this.salesteps = steps
    this.cardData = this.sales[0]
    this.supps = supps
  },
}
</script>

<style>
  body {
    margin: 0; padding: 0;
  }

  .defLay {
    display: grid;
    grid-template-rows: 60px 1fr;
    min-height: 100vh;
  }

  .defLay__header {}
  .defLay__content {}

  .defLay__view {
    height: 100%;
    box-sizing: border-box;
    transition: .3s;
  }

  .defLay__priceTag {
    padding: 0 20px; margin: 0;
  }

  .defLay__total {font-size: 21px}

  .defLay__cardCover {
    position: fixed;
    top: 0; left:0; bottom: 0; right: 0;
    background: rgba(0, 0, 0, .4);
  }

  .defLay__footNotes {
    list-style: none;
    padding: 0; margin: 20px 0;
  }

  .defLay__footItem {
    margin: 10px 0;
  }

  .defLay__footName {
    margin-right: 10px;
  }

  .defLay__footInput {
    border: none; outline: none;
    border-bottom: 1px solid black;
  }

  .backAnim-leave-active,
  .backAnim-enter-active {opacity: 0}
</style>