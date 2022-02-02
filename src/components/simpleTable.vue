<template>
<div class="simtab">
  <ul class="simtab__row" :style="{'background': row.color}"
    v-for="(row, rowIndex) in tableData" :key="rowIndex">

    <li class="simtab__cell"
      v-for="(cell, cellIndex) in row.data" :key="cell"
      :class="{
        'simtab__cell_top': rowIndex == 0,
        'simtab__cell_left': cellIndex == 0,
      }">
      {{cell}}
    </li>
  </ul>
</div>
</template>

<script>
export default {
  props: ['tableData', ],
  data: () => ({}),
  methods: {},
}
</script>

<style>
.simtab__row {
  display: flex;
  margin: 0; padding: 0;
  list-style: none;
}

.simtab__cell {
  display: flex;
  align-items: center;

  padding: 5px 10px;
  width: 150px;

  border-right: 1px solid black;
  border-bottom: 1px solid black;
}

.simtab__cell_left {border-left: 1px solid black}
.simtab__cell_top {border-top: 1px solid black}
</style>