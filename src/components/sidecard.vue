<template>
  <div class="sideCard" :class="{'sideCard_closed': !isOpened}">
    <sellForm class='sideCard__form' ref='sellForm'
      :saleID="cardData.ID" :saleName='cardData.NAME' :salePrice="cardData.PRICE" :comments="cardData.COMMENT"
      :supplierID="cardData.SUPPLIER_ID" :city="cardData.CITY" :orderDate="cardData.CREATE_DATE"
      :stepID="cardData.STATUS_ID" :stepsArr="steps" :suppsArr="suppliers" :key="cardData.ID">
    </sellForm>

    <sellTable :color="incomeColor" :forWhatArr="services" :payers="payers"
      class="sideCard__table" ref='incomeTable' tableHeader="Доходы" :suppsArr="payers"
      @cellChanged='onCellChange' :tableData="cardData.INCOME_TABLE">
    </sellTable>
    <p class="sideCard__priceTag">Всего: {{fNumber(totalIncome)}} ₽</p>

    <sellTable :forWhatArr="services"
      class="sideCard__table" ref="costTable" :color="costsColor" :suppsArr="payers"
      @cellChanged='onCellChange' tableHeader="Расходы" :tableData="cardData.COSTS_TABLE">
    </sellTable>  
    <p class="sideCard__priceTag">Всего: {{fNumber(totalCost)}} ₽</p>
    <p class="sideCard__total">Итого: {{fNumber(totalIncome + totalCost)}} ₽</p>

    <ul class="sideCard__footNotes">
      <li class="sideCard__footItem">
        <span class="sideCard__footName">Трек номер</span>
        <input class="sideCard__footInput" type="text" v-model="cardData.TRACK_NUMBER">
      </li>
      
      <li class="sideCard__footItem">
        <span class="sideCard__footName">Дата прихода</span>
        <input class="sideCard__footInput" type="date" v-model="cardData.ARRIVAL_DATE">
      </li>
    </ul>


    <div class="sideCard__optButtons">
      <button class="headNav__addButton sideCard__button"
        :class="{'headNav__addButton_green': isSaved}"
        @click="saveData">
        Сохранить
      </button>

      <button class="headNav__addButton sideCard__button headNav__addButton_red"
      @click="deleteSale">
        Удалить
      </button>

      <button class="headNav__addButton sideCard__button"
        @click="$emit('closeEvent')">
        Закрыть
      </button>
    </div>
  </div>
</template>

<script>
import sellForm from './sellForm.vue'
import sellTable from "./sellTable.vue";


export default {
  emits: ['closeEvent', 'initPush'],
  props: ['cardData', 'suppliers', 'steps', 'payers', 'services'],
  components: {sellForm, sellTable},

  data: () => ({
    isOpened: false,
    isSaved: false,
    totalIncome: 0,
    totalCost: 0,
    incomeColor: '#55efc4',
    costsColor: '#ff7675',
  }),

  computed: {
    total () {return this.totalIncome + this.totalCost}
  },

  methods: {
    fNumber (number) {
      return this.$formatter.withSpaces(Number(number))
    },

    toggleCard () {
      this.isOpened = !this.isOpened

      if (this.isOpened) {
        this.$refs.incomeTable.updateData()
        this.$refs.costTable.updateData()
        this.getTotal()
      }
    },

    initPush (text, color) {
      this.$emit('initPush', text, color)
    },

    async deleteSale () {
      let res = await this.$base.deleteSale(this.cardData.ID)
      this.initPush('Заказ удален', 'red')
      console.log(res)
    },

    async saveData () {
      let form  = await this.$refs.sellForm.getData()
      let income = await this.$refs.incomeTable.getTableData()
      let costs = await this.$refs.costTable.getTableData()
      let resultArr = {}
      let salesArr, checkSale, res

      resultArr.OLD_ID = form.oldID
      resultArr.ID = form.id
      resultArr.NAME = form.name
      resultArr.PRICE = form.price
      resultArr.SUPPLIER_ID = form.supplier
      resultArr.CITY = form.city
      resultArr.CREATE_DATE = form.orderDate
      resultArr.STATUS_ID = form.step
      resultArr.INCOME_TABLE = income
      resultArr.COSTS_TABLE = costs
      resultArr.TOTAL = this.total
      resultArr.TRACK_NUMBER = this.cardData.TRACK_NUMBER
      resultArr.ARRIVAL_DATE = this.cardData.ARRIVAL_DATE
      resultArr.COMMENT = form.comments

      salesArr = await this.$base.getTable('SALES')
      checkSale = false

      for (let sale of salesArr) {
        if (resultArr.OLD_ID == sale.ID) {checkSale = true}
      }

      if (checkSale) {res = await this.$base.updateSale(resultArr)}
      else {res = await this.$base.addSale(resultArr)}

      this.$emit('initPush', 'Карточка сохранена', '#27ae60')
      return res
    },

    onCellChange(col) {if (col == 0) {this.getTotal()}},
    getTotal () {
      let incomeTable, costsTable
      let income = 0
      let costs = 0

      incomeTable = this.$refs.incomeTable.getTableData()
      incomeTable = JSON.parse(incomeTable)
      incomeTable.forEach(row => income += Number(row.data[0]))

      costsTable = this.$refs.costTable.getTableData()
      costsTable = JSON.parse(costsTable)
      costsTable.forEach(row => costs += Number(row.data[0]))

      this.totalIncome = income
      this.totalCost = costs
    },
  },
}
</script>

<style>
  .sideCard__back {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;

    background: rgba(0, 0, 0, 0.4);
    transition: .2s;
  }

  .sideCard {
    position: fixed;
    top: 0; bottom: 0; right: 0; 
    padding: 20px;
    overflow: scroll;

    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-width: 300px;

    background: white;
    transition: .5s;
  }

  .sideCard_closed {transform: translateX(100%)}
  .sideCard__table {margin: 20px 0;}

  .sideCard__footNotes {
    list-style: none;
    padding: 0; margin: 20px 0;
  }

  .sideCard__footItem {margin: 10px 0}
  .sideCard__footName {margin: 0 10px 0 0}
  .sideCard__footInput {
    border: none; outline: none;
    border-bottom: 1px solid black;
  }

  .sideCard__button {margin: 0 20px 0 0}
  .sideCard__total {font-weight: 500;}
</style>