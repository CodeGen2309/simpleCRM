<template>
  <section class="defLay">
    <head-nav class="defLay__header" :items="menuItems" @addClick='createBlankSale'>
    </head-nav>

    <div class="defLay__content">
      <router-view class="defLay__view" v-slot='{Component}'>
        <transition name="backAnim">
          <component :is="Component" @clickOnSale="openSale" />
        </transition>
      </router-view>
    </div>

    <transition name = 'backAnim'>
      <div v-if="cardIsOpened" class="sideCard__back" @click="toggleCard"></div>
    </transition>

    <sideCard ref='sideCard' :cardData="cardData"
      @initPush="initPush" :payers="payers"
      :services="services"
      :steps="salesteps" :suppliers="supps"
      @closeEvent="toggleCard">
    </sideCard>

    <transition name="pushAnim">
      <div class="defLay__push" v-if="pushIsActive"
      :style="{'background': pushColor}">
        <p class="defLay__pushText">{{pushText}}</p>
      </div>
    </transition>
  </section>
</template>



<script>
import headNav from '../components/headNav.vue'
import sellForm from '../components/sellForm.vue'
import sellTable from '../components/sellTable.vue'
import sideCard from '../components/sidecard.vue'
import push from '../components/push.vue'

export default {
  components: { headNav, sellForm, sellTable, sideCard, push},
  emits: [],
  data: () => ({
    sales: [],
    salesteps: [],
    payers: null,
    cardData: {},
    pushSwitcher: true,
    cardIsOpened: false,
    pushText: 'HELLOOO BITHEZZZ',
    pushIsActive: false,
    pushColor: 'rgba(0, 0, 0, .8)',
    pushTimeout: 2,
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
    toggleCard () {
      this.cardIsOpened = !this.cardIsOpened
      this.$refs.sideCard.toggleCard()
    },

    initPush (text, color) {
      if (color) {this.pushColor = color}

      this.pushText = text
      this.pushIsActive = true

      setTimeout(() => {
        this.pushIsActive = false
        this.pushColor = 'rgba(0, 0, 0, .8)'
      }, this.pushTimeout*1000);
    },

    openSale (sale) {
      this.cardData = sale

      setTimeout(() => {
        this.toggleCard()
      }, 100)
    },

    createBlank () {
      let blank = {}
      blank.NAME = ''
      blank.PRICE = ''
      blank.SUPPLIER_ID = 1
      blank.CITY = ''
      blank.CREATE_DATE = ''
      blank.STATUS_ID = 1
      blank.INCOME_TABLE = '[]'
      blank.COSTS_TABLE = '[]'
      blank.TOTAL = 0
      blank.TRACK_NUMBER = ''
      blank.ARRIVAL_DATE = ''
      blank.COMMENT = ''

      return blank
    },

    createBlankSale () {
      this.cardData = this.createBlank()
      setTimeout(() => {this.toggleCard()}, 100);
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
      return await this.$base.getTable('SALES')
    },

    async getSaleSteps () {
      return await this.$base.getTable('SALESTEPS')
    },

    async getPayers () {
      return await this.$base.getTable('PAYERS')
    },

    async getServices () {
      return await this.$base.getTable('SERVICES')
    },

    async getSupps () {
      return await this.$base.getTable('SUPPLIERS')
    },
  },

  print (item) {
    console.log(item)
  },

  async created () {
    let sales = await this.getSales()
    let steps = await this.getSaleSteps()
    let payers = await this.getPayers()
    let services = await this.getServices()
    let suppliers = await this.getSupps()

    sales.forEach(item => this.sales.push(item))
    this.salesteps = steps

    this.cardData = this.createBlank()
    this.supps = suppliers
    this.services = services
    this.payers = payers
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
    height: calc(100vh - 60px);
    overflow: scroll;
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

  .defLay__push {
    position: fixed;
    bottom: 20px; left: 20px;

    padding: 10px 20px; border-radius: 3px;
    color: white; font-weight: 100;
    
    transition: .3s;
  }
  .defLay__pushText {
    margin: 0; padding: 0;
  }

  .backAnim-leave-active,
  .backAnim-enter-active {opacity: 0}

  .pushAnim-enter-active {
    opacity: 0;
    transform: translateY(40px)
  }

  .pushAnim-leave-active {
    opacity: 0;
    transform: translateY(-40px)
  }
</style>