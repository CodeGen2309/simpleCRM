<template>
  <ul class="sellForm">
    <li class="sellForm__row">
      <span class="sellForm__cell sellForm__cell_title">№</span>
      <input class="sellForm__input" type="text"
      :value="resArr.id" @change="changeID($event.target.value)">
    </li>
    <li class="sellForm__row">
      <span class="sellForm__cell sellForm__cell_title">Товар</span>
      <span class="sellForm__cell sellForm__cell_value">
        <input class="sellForm__input" type="text" v-model="resArr.name">
      </span>
    </li> 
    <li class="sellForm__row">
      <span class="sellForm__cell sellForm__cell_title">Цена товара</span>
      <span class="sellForm__cell sellForm__cell_value">
        <input class="sellForm__input" type="number" v-model="resArr.price">
        <span>₽</span>
      </span>
    </li>
    <li class="sellForm__row">
      <span class="sellForm__cell sellForm__cell_title">Поставщик</span>
      <span class="sellForm__cell sellForm__cell_value">
        <select class="sellForm__select" @change="changeSelect('supplier', $event)">
          <option class="sellForm__selectItem"
            v-for="supp in suppsArr" :key="supp" :value="supp.ID"
            :selected="supp.ID == supplierID">
            {{supp.NAME}}
          </option>
        </select>
      </span>
    </li>
    <li class="sellForm__row">
      <span class="sellForm__cell sellForm__cell_title">Город</span>
      <span class="sellForm__cell sellForm__cell_value">
        <input class="sellForm__input" type="text" v-model="resArr.city">
      </span>
    </li>
    <li class="sellForm__row">
      <span class="sellForm__cell sellForm__cell_title">Дата заказа</span>
      <span class="sellForm__cell sellForm__cell_value">
        <input class="sellForm__dateInput" type="date" v-model="resArr.orderDate">
      </span>
    </li>
    <li class="sellForm__row">
      <span class="sellForm__cell sellForm__cell_title">Статус</span>
      <span class="sellForm__cell sellForm__cell_value">
        <select class="sellForm__select" @change="changeSelect('step', $event)">
          <option class="sellForm__selectItem"
            v-for="step in stepsArr" :key="step"
            :selected="resArr.step == step.ID"
            :value="step.ID">
            {{step.NAME}}
          </option>
        </select>
      </span>
    </li>
    <li class="sellForm__row">
      <span class="sellForm__cell sellForm__cell_title">Комментарий</span>
      <span class="sellForm__cell sellForm__cell_value">
        <textarea class="sellForm__textArr" type="textarea" v-model="resArr.comments"></textarea>
      </span>
    </li>
  </ul>
</template>

<script>
export default {
  props: [
    'saleID', 'saleName', 'salePrice', 'supplierID',
    'city', 'orderDate', 'stepID', 'suppsArr','stepsArr', 'comments'
  ],

  data: () => ({
    resArr: {},
  }),

  methods: {
    getData () {
      return this.resArr
    },

    changeSelect (selectName, event) {
      this.resArr[selectName] = event.target.value
    },

    changeID (item) {
      this.resArr.oldID = this.resArr.id
      this.resArr.id = item
    }
  },

  created () {
    this.resArr = {
      oldID: this.saleID || null ,
      id: this.saleID || null ,
      name: this.saleName,
      price: this.salePrice,
      supplier: this.supplierID,
      city: this.city,
      orderDate: this.orderDate,
      step: this.stepID || 1,
      comments: this.comments,
    }
  },
}
</script>

<style>
  .sellForm {
    list-style: none;
    margin: 0; padding: 0;
    min-width: 500px;
  }
  .sellForm__row {
    display: flex;
    align-items: center;
    padding: 10px 0;
  }

  .sellForm__cell {width: 50%}
  .sellForm__cell_title {color: gray}
  .sellForm__cell_value {}

  .sellForm__input {
    border: none; outline: none;
    border-bottom: 1px solid gray;
  }

  .sellForm__textArr {
    outline: none;
  }

  .sellForm__select {
    padding: 10px 0;
    border: none; outline: none;
    border-bottom: 1px solid gray;
    background: none; max-width: 200px;
  }

  .sellForm__dateInput {
    margin: 10px 0; padding: 5px 0;
    border: none; outline: nnoe;
    border-bottom: 1px solid gray;
  }

  .sellForm__dateInput:focus {
    border: none; outline: none;
  }
</style>