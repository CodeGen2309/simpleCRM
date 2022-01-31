<template>
  <div class="sTable__holder">
    <h3 class="sTable__header">{{tableHeader}}</h3>

    <ul class="sTable">
      <li class="sTable__row" v-for="(row, rowIndex) in tableArr" :key="row[0]">
        <span class="sTable__cell sTable__cell_addRow" @click="addRow(rowIndex + 1)">+</span>

        <span class="sTable__cell sTable__cell_first"
        :style = '{background: row.color}'
        :class="{'sTable__cell_lastRow': rowIndex == tableArr.length - 1}">
          <input class="sTable__input" type="text"
          @change="summChanged(rowIndex, $event.target.value)"
          :value="row.data[0]">
        </span>

        <span class="sTable__cell"
        :style = '{background: row.color}'
        :class="{'sTable__cell_lastRow': rowIndex == tableArr.length - 1}">
          <select class="sTable__input" type="text"
          @change="row.data[1] = $event.target.value">
            <option v-for="supp in suppsArr" :value="supp.ID" :key="supp.ID"
            :selected="supp.ID == row.data[1]">
              {{supp.NAME}}
            </option>
          </select>
        </span>

        <span class="sTable__cell" :style = "{background: row.color}"
        :class="{'sTable__cell_lastRow': rowIndex == tableArr.length - 1}">
          <input class="sTable__input" type="date" :value="row.data[2]" @change="row.data[2] = $event.target.value">
        </span>

        <span class="sTable__cell"
        :style = '{background: row.color}'
        :class="{'sTable__cell_lastRow': rowIndex == tableArr.length - 1}">
          <select class="sTable__input" type="text"
          @change="row.data[3] = $event.target.value">
            <option v-for="fw in forWhatArr" :value="fw.ID" :key="fw.ID"
            :selected="fw.ID == row.data[3]">
              {{fw.NAME}}
            </option>
          </select>
        </span>

        <span class="sTable__cell sTable__cell_color"
          :style = '{background: row.color}'
          :class="{'sTable__cell_lastRow': rowIndex == tableArr.length - 1,}">
          <input class="sTable__input"  type="checkbox">
        </span>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: [
    'tableData', 'tableSize', 'tableHeader', 'color',
    'suppsArr', 'suppID', 'forWhatArr', 'forWhatID', 'payers'
  ],
  emits: ['cellChanged', ],

  data: () => ({
    tableArr : [],
  }),

  methods: {
    addRow (index) {
      let colCount = this.tableArr[0]['data'].length
      let newRow = {color: this.color, data: [],}

      for (let i = 0; i < colCount; i++) {newRow.data.push('')}
      this.tableArr.splice(index, 0, newRow)
    },

    getTableData () {
      return JSON.stringify(this.tableArr)
    },

    createBlankTable () {
      let blank, blankRow

      blankRow = ['', '3', '', '']
      blank = [
        {color: this.color, data: blankRow.slice()},
        {color: this.color, data: blankRow.slice()}
      ]

      return blank
    },

    summChanged (rowIndex, value) {
      this.tableArr[rowIndex].data[0] = value
      this.$emit('cellChanged', 0)
    },

    updateData () {
      if (this.tableData == '[]') {this.tableArr = this.createBlankTable()}
      else if (this.tableData == undefined) {this.tableArr = this.createBlankTable()}
      else {this.tableArr = JSON.parse(this.tableData)}
      console.log(this.forWhatArr)
      console.log(this.suppsArr)
    },
  },

  created () {
    this.updateData()
    this.tableColor = this.color
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
    max-width: 20px;

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