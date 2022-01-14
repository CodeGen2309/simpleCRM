<template>
  <div class="sTable__holder">
    <h3 class="sTable__header">{{tableHeader}}</h3>

    <ul class="sTable">
      <li class="sTable__row" v-for="(row, rowIndex) in tableArr" :key="row[0]">

        <span class="sTable__cell sTable__cell_addRow" @click="addRow(rowIndex + 1)">+</span>

        <span class="sTable__cell"
          v-for="(cell, cellIndex) in row.data" :key="cell"
          @change="$emit('cellChanged')"
          :style = '{background: row.color}'
          :class="{
            'sTable__cell_lastRow': rowIndex == tableArr.length - 1,
            'sTable__cell_first': cellIndex == 0,
          }">

          <input class="sTable__input" type="text" v-model.lazy="tableArr[rowIndex]['data'][cellIndex]">
        </span>

        <span class="sTable__cell sTable__cell_color"
          :style = '{background: row.color}'
          :class="{'sTable__cell_lastRow': rowIndex == tableArr.length - 1,}">
          <input class="sTable__input"  type="checkbox">
        </span>

        <span class="sTable__cell sTable__cell_color"
          :class="{'sTable__cell_lastRow': rowIndex == tableArr.length - 1,}">
          <input class="sTable__input" type="color" v-model="tableArr[rowIndex]['color']">
        </span>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: ['tableData', 'tableSize', 'tableHeader'],

  data: () => ({
    tableArr : [],
  }),

  methods: {
    addRow (index) {
      let colCount = this.tableArr[0]['data'].length
      let newRow = {color: '', data: [],}

      for (let i = 0; i < colCount; i++) {newRow.data.push('')}
      this.tableArr.splice(index, 0, newRow)
    },

    selectColor (index, color) {
      this.tableArr[index]['color'] = color
    },

    printTable () {
      console.log(this.tableArr)
    },

    getTableData () {
      return JSON.stringify(this.tableArr)
    }
  },

  created () {
    if (this.tableData != undefined) {
      this.tableArr = this.tableData
    }

    if (this.tableSize != undefined) {
      let cols = this.tableSize[0]
      let rows = this.tableSize[1]
      let table = []

      for (let i = 0; i < rows; i++) {
        let row = {color: '', data: []}
        for (let j = 0; j < cols; j++) { row.data.push('') }
        table.push(row)
      }

      this.tableArr = table
    }
  }
}
</script>

<style>
  .sTable {
    margin: 0; padding: 0;
    list-style: none;
  }

  .sTable__header {
    margin: 10px 20px; padding: 0;
  }

  .sTable__row {
    display: flex;
  }

  .sTable__row:hover .sTable__cell_addRow {
    opacity: 1;
  }

  .sTable__cell {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-grow: 1;

    border: .5px solid black;
    border-left: none;
    border-bottom: none;
  }

  .sTable__cell_first {
    border-left: 1px solid black;
  }

  .sTable__cell_lastRow {
    border-bottom: 1px solid black;
  }

  .sTable__cell_addRow {
    display: flex;
    justify-content: center;
    align-items: center;

    border: 1px solid gainsboro;
    background: gainsboro;
    width: 20px;

    cursor: pointer;
    transition: .3s;
    opacity: 0;
  }

  .sTable__cell_color {
    min-width: 40px;
  }

  .sTable__input {
    width: 100%;
    padding: 5px;
    border: none; outline: none;
    background: none;
  }

  .sTable_addButton {
    display: flex;
    width: 100%; height: 100%;

    background: aqua;
    border: none;

    cursor: pointer;
    transition: .3s;
    opacity: 0;
  }
</style>