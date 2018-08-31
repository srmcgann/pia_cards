<template>
  <div class="businessDiv">
    <div v-if="showForm" class="introDiv">
      Enter your zip code to search for<br>nearby participating businesses...<br>
      <form @submit.prevent="lookupBusinesses">
        <input ref="zipinput" class="zipInput" placeholder="enter zip" v-model="userZip"/>
        <button class="enterZipButton">Go</button>
      </form>
    </div>
    <div v-else-if="businessesFound.length === 0 && !zipNotFound" class="resultsDiv">
      <br><br>
      Searching {{businessCount}} businesses for the closest to you...<br><br>
      This might take a moment.<br>
      <img src="../assets/hourglass.gif">
    </div>
    <div v-else-if="zipNotFound">
      <div class="sorryCharlie">That zip wasn't found in our database.</div>
      <button @click="clearForm()" class="button">Search Again</button><br><br>
    </div>
    <div v-else class="resultsDiv">
      <button @click="clearForm()" class="button">Search Again</button><br><br>
      <div style="float:left;margin-top:-40px;">Nearest 10 Results...</div>
      <div v-for="business in businessesFound" class="innerBusinessDiv" :key="business.id">
        <div class="companyName">{{business.company_name}}</div>
        <table class="companyTable">
          <tr>
            <td>Distance:</td><td>{{Math.round(business.distance*100)/100}} miles</td>
          </tr>
          <tr>
            <td>Founded:</td><td>{{business.year_founded}} </td>
          </tr>
          <tr>
            <td>Website:</td><td><a :href="business.url" target="_blank">{{business.url}}</a></td>
          </tr>
          <tr>
            <td>City, State:</td><td>{{business.city}}, {{business.state}}</td>
          </tr>
          <tr>
            <td>Zip Code:</td><td>{{business.zip_code}}</td>
          </tr>
          <tr>
            <td>Category:</td><td>{{business.company_category}}</td>
          </tr>
        </table>
        <div class="description" v-html="business.description"></div>
        <div class="clear"></div>
        <iframe
          class="companyMap"
          scrolling="yes"
          frameborder="0"
          marginheight="0"
          marginwidth="0"
          :src="`https://maps.google.com/maps?q=${business.lat},${business.lng}&z=7&output=embed`"
         >
         </iframe>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Business',
  data () {
    return {
      userZip: '',
      backend: 'https://jsbot.net/pia_cards/static/getZips.php',
      showForm: true,
      businessesFound: [],
      businessCount: 0,
      content: '',
      zipNotFound: false
    }
  },
  methods: {
    clearForm () {
      this.userZip = ''
      this.showForm = true
      this.$nextTick(() => this.$refs.zipinput.focus())
    },
    validateZip (zip) {
      return zip !== '' &&
             zip > 600 &&
             zip < 100000
    },
    getPrelimData () {
      this.businessesFound = []
      let data = {query: 'get prelimData'}
      fetch(this.backend, {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
          'Content-Type': 'application/json'
        }
      }).then(res => res.json())
        .then(response => {
          this.showForm = false
          this.businessCount = response
        }).catch(error => console.error('Error:', error))
    },
    lookupBusinesses () {
      if (this.validateZip(this.userZip)) {
        this.getPrelimData()
        let data = {query: 'get businesses', zip: this.userZip, page: 1}
        fetch(this.backend, {
          method: 'POST',
          body: JSON.stringify(data),
          headers: {
            'Content-Type': 'application/json'
          }
        }).then(res => res.json())
          .then(response => {
            this.businessesFound = response
            if (!response.length) {
              this.zipNotFound = true
            }
          }).catch(error => console.error('Error:', error))
      }
    }
  },
  mounted () {
    this.$nextTick(() => this.$refs.zipinput.focus())
  }
}
</script>

<style scoped>
.companyName {
  font-size: 36px;
  margin-bottom: 5px;
}
.resultsDiv {
  font-size: 20px;
}
.description {
  line-height: 1em;
  font-size: 16px;
  text-align: justify;
  width: calc(100% - 15px);
  max-height:100px;
  overflow: auto;
  padding: 5px;
  background: #fff;
  color: #000;
}
.businessDiv {
  text-align: center;
  margin-top: -40px;
}
.clear {
  clear: both;
}
.companyMap {
  width: calc(100% - 5px);
  height: 300px;
  margin:0px;
  margin-top: 20px;
}
.companyTable {
  margin-bottom: 10px;
}
a {
  color: #4a6;
  text-decoration: none;
}
td {
  padding: 0px;
  padding-right: 15px;
  padding-left: 0px;
}
.innerBusinessDiv {
  text-align: left;
  margin-bottom: 35px;
  padding-bottom: 15px;
  border-radius: 15px;
  background: #f0f8ff;
  border: 5px solid #cde;
  padding:10px;
}
.enterZipButton {
  font-size: 28px;
  background: #dfd;
  border: 1px solid #6f6;
  color: #2a2;
  border-radius: 5px;
}
.introDiv {
  font-size: 24px;
  margin-top: 100px;
  margin-bottom: 100px;
}
button {
  cursor: pointer;
}
button:focus {
  outline:0;
}
.button {
  font-size: 24px;
  background: #9ef;
  border: none;
  padding: 5px;
  padding-left: 15px;
  padding-right: 15px;
  border-radius: 10px;
}
.sorryCharlie {
  font-size: 24px;
  margin: 40px;
}
.zipInput {
  margin-top: 20px;
  font-size: 28px;
  width: 150px;
  text-align: center;
}
</style>
