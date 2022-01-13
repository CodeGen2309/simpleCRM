<template>
  <div class="sideCard" :class="{'sideCard_closed': !isOpened}">
    <sellForm class = 'sideCard__form'
      :saleID="cardData.ID" :saleName='cardData.NAME' :salePrice="cardData.PRICE"
      :supplierID="cardData.SUPPLIER_ID" :city="cardData.CITY" :orderDate="cardData.CREATE_DATE" :stepID="cardData.STATUS_ID">
    </sellForm>

    <sellTable
      class="sideCard__table" ref='incomeTable' tableHeader="Доходы"
      @cellChanged = 'getCosts' :tableSize="[4, 2]">
    </sellTable>
    <p class="sideCard__priceTag">Всего: {{totalIncome}}</p>

    <sellTable
      class="sideCard__table" ref="costTable"
      @cellChanged = 'getCosts' tableHeader="Расходы" :tableSize="[4, 2]">
    </sellTable>  
    <p class="sideCard__priceTag">Всего: {{cardData}}</p>
    <p class="sideCard__total">Итого: {{totalIncome + totalCost}}</p>

    <ul class="sideCard__footNotes">
      <li class="sideCard__footItem">
        <span class="sideCard__footName">Трек номер</span>
        <input class="sideCard__footInput" type="text">
      </li>
      
      <li class="sideCard__footItem">
        <span class="sideCard__footName">Дата прихода</span>
        <input class="sideCard__footInput" type="text">
      </li>
    </ul>


    <div class="optButtons"></div>
  </div>
</template>

<script>
import sellForm from './sellForm.vue'
import sellTable from "./sellTable.vue";


export default {
  props: ['cardData', 'suppliers', 'steps'],
  components: {sellForm, sellTable},

  data: () => ({
    isOpened: false,
  }),

  methods: {
    toggleCard () {this.isOpened = !this.isOpened}
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
</style>