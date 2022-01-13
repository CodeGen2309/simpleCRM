<template>
  <div class="headNav">
    <ul class="headNav__menu">
      <li class="headNav__menuItem" v-for="(item) in items" :key="item.id">
        <router-link class="headNav__menuLink" :to="item.link">{{item.name}}</router-link>
      </li>
    </ul>

    <button class="headNav__addButton" @click='$emit("addClick")'>+ Новая сделка</button>


    <router-link class="headNav__icon" to="/setup">
      <img src="icons/gear.svg" alt="">
    </router-link>
  </div>
</template>

<script>
  export default {
    props: ['items'],
    emits: ['addClick', 'clickOnGear'],
    data: () => ({}),
  }
</script>

<style>
  .headNav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 0; padding: 0;
    
    background: white;
    box-shadow: 0 0 4px gray;
  }

  .headNav__menu {
    display: flex;
    align-items: stretch;

    width: 100%; min-height: 60px;
    margin: 0; padding: 0;
    list-style: none;
  }

  .headNav__menuItem {
    display: flex;
    justify-content: stretch;
    align-items: stretch;
  }

  .headNav__menuLink {
    display: flex;
    justify-content: center;
    align-items: center;

    padding: 20px 30px;
    text-decoration: none;
    color: inherit;

    transition: .1s;
  }

  .headNav__menuLink:hover {
    background: #ecf0f1;
  }

  .router-link-active {
    font-weight: 600;
    background: #ecf0f1;
  }

  .headNav__addButton {
    min-width: 150px;
    padding: 10px;
    text-transform: uppercase;

    background: #2F88DA;
    color: white;

    border: none;
    border-radius: 5px;

    cursor: pointer;
    transition: .3s;
  }

  .headNav__addButton:hover {
    background: #66a5e1;
  }

  .headNav__icon {
    display: flex;
    width: 25px; height: 25px;
    padding: 10px; margin: 0 10px;
    border-radius: 10px;

    cursor: pointer;
    transition: .3s;
  }

  .headNav__iconImg {}
</style>