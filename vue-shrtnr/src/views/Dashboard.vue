<template>
<div class="form-signin">
  <form @submit.prevent="submit">
    <h1 class="h3 mb-3 fw-normal text-center">Shorten Your URL</h1>

    <input v-model="form.user_id" type="hidden">
    <input v-model="form.original_link" type="text" class="form-control" placeholder="Enter a long URL to make a ShortURL" required>

    <button class="w-100 btn btn-lg btn-success" type="submit">Make ShortURL!</button>
  </form>
  <div class="shortUrl" v-if="short_url">
    <span class="text-dark text-center font-weight-bold">Your Short URL : </span> 
    <a href="#" @click="linkClick(original_url)"> {{ short_url}} </a>
    <button class="mt-2 btn btn-md btn-secondary" @click="clearBtn">Clear</button> <span class="customize"></span>
    <button class="mt-2 btn btn-md btn-primary" @click="customizeBtn">Customize</button>
  </div>
</div>
<div class="container tbl-link">
    <h1 class="h3 mb-3 mt-5 fw-normal text-center">Your Links List</h1>
   
    <table class="table table-striped table-bordered">
        <thead>
            <tr class="text-center bg-dark text-white">
                <th scope="col">#</th>
                <th scope="col" style="width:50%">Long URL</th>
                <th scope="col">Short URL</th>
                <th scope="col">Total Click</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, index) in all_links" :key="item">
                <th scope="row">{{index+1}}</th>
                <td style="width:50%">{{item.original_link}}</td>
                <td class="text-center" v-if="item.is_active">
                    <a  href="#" @click="itemClick(item.user_id, item.id, item.original_link)"> {{ item.short_link }} </a>
                </td>
                <td class="text-center" v-else>
                    {{ item.short_link }} <sub style="color:red;">[Expired]</sub>
                </td>
                <td class="text-center text-dark">{{ item.click != null ? item.click.click_count : 0 }}</td>
            </tr>
        </tbody>
</table>
</div>
  
</template>

<script>
import { mapGetters} from 'vuex'
import axios from 'axios'


export default {
  name: "Dashboard",

  data(){
      return {
          form: {
            original_link: '',
            user_id: '',
          },
          short_url: '',
          original_url: '',
          link_id: '',
          all_links: [],
      }
  },

  methods:{

    async submit(){
            const response = await axios.post('http://127.0.0.1:8000/api/url-shortener-user/', this.form)
            this.short_url = response.data.shortUrl
            this.original_url = response.data.originalURL
            this.link_id = response.data.link_id

            const responses = await axios.get('http://127.0.0.1:8000/api/get-user-links')
            this.all_links = responses.data
      },

      async linkClick (org_link){
        const formDatae = {
            user_id : this.user.id,
            user_link_id : this.link_id
        }
        const response = await axios.post('http://127.0.0.1:8000/api/link-click', formDatae)
        const responses = await axios.get('http://127.0.0.1:8000/api/get-user-links')
        this.all_links = responses.data  
        window.open(``+ org_link, '_blank')
      },

      async itemClick (userId, userLinkId, org_link){
        const formDatae = {
            user_id : userId,
            user_link_id : userLinkId
        }
        const response = await axios.post('http://127.0.0.1:8000/api/link-click', formDatae)
        const responses = await axios.get('http://127.0.0.1:8000/api/get-user-links')
        this.all_links = responses.data  
        window.open(``+org_link, '_blank')
      },

      clearBtn(){
        this.form.original_link = ''
        this.short_url = ''
        this.original_url = ''
      },

      async customizeBtn(){
          const formDatae = {
            original_link : this.form.original_link,
            user_id : this.form.user_id
          }
          const response = await axios.post('http://127.0.0.1:8000/api/update-link', formDatae)
            this.short_url = response.data.shortUrl
            this.original_url = response.data.originalURL

            const responses = await axios.get('http://127.0.0.1:8000/api/get-user-links')
            this.all_links = responses.data
      }
      
  },

  computed: {
      ...mapGetters({
          user: 'auth/user',
      })
  },

  async created() {
    const response = await axios.get('http://127.0.0.1:8000/api/get-user-links')
    this.all_links = response.data
    this.form.user_id = this.user.id
  }

}
</script>

<style scoped>
  .shortUrl{
    margin-top : 25px;
  }

  .shortUrl span{
    padding: 10px;
    font-weight: 600;
  }

  .shortUrl a{
    padding-left: 15%;
  }

  .shortUrl button{
    float: right;
  }
  .shortUrl .customize{
    float: right;
    width : 2px;
  }
</style>