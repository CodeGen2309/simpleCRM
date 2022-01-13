<template>
  <ul class="sellForm">
    <li class="sellForm__row">
      <span class="sellForm__cell sellForm__cell_title">№</span>
      <span class="sellForm__cell sellForm__cell_value">1232</span>
    </li>
    <li class="sellForm__row">
      <span class="sellForm__cell sellForm__cell_title">Товар</span>
      <span class="sellForm__cell sellForm__cell_value">
        <input class="sellForm__input" type="text">
      </span>
    </li> 
    <li class="sellForm__row">
      <span class="sellForm__cell sellForm__cell_title">Цена товара</span>
      <span class="sellForm__cell sellForm__cell_value">
        <input class="sellForm__input" type="number">
        <span>₽</span>
      </span>
    </li>
    <li class="sellForm__row">
      <span class="sellForm__cell sellForm__cell_title">Поставщик</span>
      <span class="sellForm__cell sellForm__cell_value">
        <select class="sellForm__select">
          <option class="sellForm__selectItem" value="1">СтолГрупп</option>
          <option class="sellForm__selectItem" value="2">СтолЭндКомпани</option>
          <option class="sellForm__selectItem" value="3">СтолЭндСтулКорп</option>
          <option class="sellForm__selectItem" value="4">ХардманСпектр</option>
          <option class="sellForm__selectItem" value="5">СтолСолюшн</option>
        </select>
      </span>
    </li>
    <li class="sellForm__row">
      <span class="sellForm__cell sellForm__cell_title">Город</span>
      <span class="sellForm__cell sellForm__cell_value">
        <input class="sellForm__input" type="text">
      </span>
    </li>
    <li class="sellForm__row">
      <span class="sellForm__cell sellForm__cell_title">Дата заказа</span>
      <span class="sellForm__cell sellForm__cell_value">
        <input class="sellForm__dateInput" type="date">
      </span>
    </li>
    <li class="sellForm__row">
      <span class="sellForm__cell sellForm__cell_title">Статус</span>
      <span class="sellForm__cell sellForm__cell_value">
        <select class="sellForm__select">
          <option class="sellForm__selectItem" value="1">Ждем первую поставку</option>
          <option class="sellForm__selectItem" value="2">Перерыв в отношениях</option>
          <option class="sellForm__selectItem" value="3">Обиделись</option>
          <option class="sellForm__selectItem" value="4">Задерживают поставку</option>
          <option class="sellForm__selectItem" value="5">Отгрузка</option>
        </select>
      </span>
    </li>
  </ul>
</template>

<script>
export default {
  props: ['saleID', 'saleName', 'salePrice', 'supplierID', 'city', 'orderDate', 'stepID', 'suppsArr','stepsArr', ],
  data: () => ({}),
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

  .sellForm__cell {
    width: 50%;
  }

  .sellForm__cell_title {
    color: gray;
  }

  .sellForm__cell_value {}

  .sellForm__input {
    border: none; outline: none;
    border-bottom: 1px solid gray;
  }

  .sellForm__select {
    padding: 10px 0;
    border: none; outline: none;
    border-bottom: 1px solid gray;
    background: none;
    max-width: 200px;
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