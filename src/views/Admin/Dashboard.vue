<template>
  <TopNavigationBar/>
  <v-container fluid>
    <v-row>
      <v-col cols="12" md="6">
        <v-card>
          <v-card-title
            class="bg-deep-purple-darken-4 mb-4 text-center d-flex justify-center align-center !tw-items-center"
          >
            <v-icon class="mr-1" size="x-small">mdi-book</v-icon>
            Bibliographic
          </v-card-title>
          <v-card-text>
            <v-row>
              <v-col cols="12" md="7" >
                <v-card elevation="0">
                  <v-card-text>
                    <v-row>
                      <v-col cols="12">
                        <v-text-field v-model="date_received" label="Date Received" variant="outlined" density="compact"></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12" md="6">
                        <v-text-field v-model="copy" label="No. of Copies" variant="outlined" density="compact"></v-text-field>
                      </v-col>
                      <v-col cols="12" md="6">
                        <v-radio-group v-model="type" inline>
                          <v-radio label="Book" value="Book"></v-radio>
                          <v-radio label="UM" value="UM"></v-radio>
                          <v-radio label="AV" value="AV"></v-radio>
                        </v-radio-group>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12">
                        <v-text-field v-model="title" label="Title" variant="outlined" density="compact"></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12">
                        <v-text-field v-model="author" label="Author" variant="outlined" density="compact"></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12" md="6">
                        <v-text-field v-model="publisher" label="Publisher" variant="outlined" density="compact"></v-text-field>
                      </v-col>
                      <v-col cols="12" md="6">
                        <v-text-field v-model="publicationPlace" label="Place of Publication" variant="outlined" density="compact"></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="6" md="4">
                        <v-text-field v-model="copyright" label="Copyright" variant="outlined" density="compact"></v-text-field>
                      </v-col>
                      <v-col cols="6" md="4">
                        <v-text-field v-model="volumes" label="Volume" variant="outlined" density="compact"></v-text-field>
                      </v-col>
                      <v-col cols="6" md="4">
                        <v-text-field v-model="edition" label="Edition" variant="outlined" density="compact"></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="6" md="6">
                        <v-text-field v-model="pages" label="Pages" variant="outlined" density="compact"></v-text-field>
                      </v-col>
                      <v-col cols="6" md="6">
                        <v-text-field v-model="cost_price" label="Cost Price" variant="outlined" density="compact"></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12" md="6">
                        <v-text-field v-model="source_of_fund" label="Source of Fund" variant="outlined" density="compact"></v-text-field>
                      </v-col>
                      <v-col cols="12" md="6">
                        <v-select
                          v-model="department"
                          clearable
                          label="Department"
                          :items="departments"
                          :item-props="departmentProps"
                          variant="outlined"
                          density="compact"
                        ></v-select>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12">
                        <v-text-field v-model="remarks" label="Remarks" variant="outlined" density="compact"></v-text-field>
                      </v-col>
                    </v-row>
                  </v-card-text>
                  <v-card-actions>
                    <v-btn variant="tonal" @click="resetForm">Reset</v-btn>
                    <v-btn variant="tonal" color="primary" @click="saveAccession">Save</v-btn>
                    <v-btn variant="tonal" color="error" @click="deleteAccession">Delete</v-btn>
                  </v-card-actions>
                </v-card>
              </v-col>
              <v-col cols="12" md="5">
                <v-card variant="tonal" color="primary" height="900" max-height="800" elevation="0">
                  <v-card-text>
                    <v-card variant="text">
                      <v-card-title>Accession to Edit</v-card-title>
                      <v-card-text>
                        <v-row>
                          <v-col cols="12">
                            <v-text-field v-model="accessionSearch" hint="Press Enter to Search" variant="outlined" density="compact"></v-text-field>
                          </v-col>
                        </v-row>
                      </v-card-text>
                    </v-card>
                    <v-card variant="text" class="mt-2">
                      <v-card-title>Accession List</v-card-title>
                      <v-card-text>
                        <v-row>
                          <v-col cols="12">
                            <v-list>
                              <v-list-item
                                v-for="(book, index) in filteredAccessions"
                                :key="book.id"
                                @click="populateForm(book)"
                              >
                                <v-list-item-title>{{ book.accession_number }} - {{ book.title }}</v-list-item-title>
                                <v-list-item-subtitle>{{ book.author }}</v-list-item-subtitle>
                              </v-list-item>
                            </v-list>
                            <v-progress-circular
                              v-if="loadingAccessions"
                              indeterminate
                              color="primary"
                              class="d-flex justify-center mt-4"
                            ></v-progress-circular>
                          </v-col>
                        </v-row>
                      </v-card-text>
                    </v-card>
                  </v-card-text>
                </v-card>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
        <v-card class="mt-2">
          <v-card-title
            class="bg-deep-purple-darken-4 mb-4 text-center d-flex justify-center align-center"
          >
            <v-icon class="mr-1" size="x-small">mdi-microsoft-excel</v-icon>
            Reports Generation
          </v-card-title>
          <v-card-text>
            <v-form>
              <v-row>
                <v-col cols="12" md="6">
                  <v-radio-group inline>
                    <v-radio label="Book" value="book"></v-radio>
                    <v-radio label="AV" value="av"></v-radio>
                    <v-radio label="UM" value="um"></v-radio>
                  </v-radio-group>
                  <v-radio-group>
                    <v-radio label="Bibliographies" value="bibliographies"></v-radio>
                    <v-radio label="Materials for Circulation" value="circulation"></v-radio>
                  </v-radio-group>
                </v-col>
                <v-col cols="12" md="6">
                  <v-select
                    clearable
                    label="Department"
                    :items="departments"
                    :item-props="departmentProps"
                    variant="outlined"
                    density="compact"
                  ></v-select>
                  <div class="d-flex justify-center">
                    <v-date-input
                      variant="outlined"
                      v-model="model"
                      label="Select range (From - To)"
                      max-width="368"
                      multiple="range"
                      density="compact"
                    ></v-date-input>
                  </div>
                </v-col>
              </v-row>
              <v-btn variant="tonal" color="primary">Generate</v-btn>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" md="6">
        <v-card>
          <v-card-title
            class="bg-deep-purple-darken-4 mb-4 text-center d-flex justify-center align-center"
          >
            <v-icon class="mr-1" size="x-small">mdi-book-open-page-variant</v-icon>
            List of Books
          </v-card-title>
          <v-card-text>
            <v-text-field v-model="bookSearch" variant="outlined" label="Search" outlined dense solo-inverted clearable append-icon="mdi-magnify"></v-text-field>
            <v-data-table
              v-model:items-per-page="itemsPerPage"
              :headers="headers"
              :items="filteredBooks"
              :search="bookSearch"
              class="elevation-1"
            ></v-data-table>
            <v-progress-circular
              v-if="loadingBooks"
              indeterminate
              color="primary"
              class="d-flex justify-center mt-4"
            ></v-progress-circular>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import axios from 'axios';
import { useStore } from '@/stores/store';
import TopNavigationBar from "@/components/navbars/TopNavigationBar.vue";

export default {
  components: { TopNavigationBar },
  data: () => ({
    model: null,
    bookSearch: '',
    accessionSearch: '',
    itemsPerPage: 10,
    loadingBooks: false,
    loadingAccessions: false,
    departments: [
      { name: 'CAS', description: '' },
      { name: 'CTDE', description: '' },
      { name: 'CCS', description: '' },
      { name: 'CEA', description: '' },
      { name: 'CHS', description: '' },
      { name: 'CTHBM', description: '' },
      { name: 'GRD', description: '' },
      { name: 'DON', description: 'Donated Books' },
      { name: 'FIL', description: 'Filipiniana' },
      { name: 'FIC', description: 'Fiction' },
      { name: 'BKL', description: 'Bikoliana' },
      { name: 'RES', description: 'Reserved' },
      { name: 'REF', description: 'General References' },
    ],
    books: [],
    headers: [
      { title: 'Accession Number', value: 'accession_number', align: 'start', sortable: true },
      { title: 'Date Received', value: 'date_received', align: 'start', sortable: true },
      { title: 'Source of Fund', value: 'source_of_fund', align: 'start', sortable: true },
      { title: 'Cost Price', value: 'cost_price', align: 'start', sortable: true },
      { title: 'Remarks', value: 'remarks', align: 'start', sortable: true },
      { title: 'ISBN', value: 'isbn', align: 'start', sortable: true },
      { title: 'Date Accession', value: 'dateaccession', align: 'start', sortable: true },
      { title: 'Title', value: 'title', align: 'start', sortable: true },
      { title: 'Author', value: 'author', align: 'start', sortable: true },
      { title: 'Edition', value: 'edition', align: 'start', sortable: true },
      { title: 'Volumes', value: 'volumes', align: 'start', sortable: true },
      { title: 'Pages', value: 'pages', align: 'start', sortable: true },
      { title: 'Copyright', value: 'copyright', align: 'start', sortable: true },
      { title: 'Publisher', value: 'publisher', align: 'start', sortable: true },
      { title: 'Department', value: 'department', align: 'start', sortable: true },
      { title: 'Copy', value: 'copy', align: 'start', sortable: true },
      { title: 'Encoder', value: 'encoder', align: 'start', sortable: true },
      { title: 'Type', value: 'type', align: 'start', sortable: true },
      { title: 'Publication Place', value: 'publicationPlace', align: 'start', sortable: true },
      { title: 'Call Number', value: 'call_no', align: 'start', sortable: true }
    ],
    accession_number: '',
    date_received: '',
    source_of_fund: '',
    cost_price: '',
    remarks: '',
    isbn: '',
    dateaccession: '',
    title: '',
    author: '',
    edition: '',
    volumes: '',
    pages: '',
    copyright: '',
    publisher: '',
    department: '',
    copy: '',
    encoder: '',
    type: '',
    publicationPlace: '',
    call_no: '',
    accession_id: null,
  }),

  computed: {
    filteredBooks() {
      return this.books.filter(book => {
        return book.title.toLowerCase().includes(this.bookSearch.toLowerCase()) ||
          book.author.toLowerCase().includes(this.bookSearch.toLowerCase());
      });
    },
    filteredAccessions() {
      return this.books.filter(book => {
        return book.accession_number.toLowerCase().includes(this.accessionSearch.toLowerCase()) ||
          book.title.toLowerCase().includes(this.accessionSearch.toLowerCase()) ||
          book.author.toLowerCase().includes(this.accessionSearch.toLowerCase());
      });
    }
  },

  methods: {
    departmentProps(item) {
      return {
        title: item.name,
        subtitle: item.description,
      };
    },
    fetchBooks() {
      this.loadingBooks = true;
      const store = useStore();
      axios.get(`${store.appURL}/admin.php?load`, { withCredentials: true })
        .then(response => {
          this.books = response.data;
        })
        .catch(error => {
          console.error('Error fetching books:', error);
        })
        .finally(() => {
          this.loadingBooks = false;
        });
    },
    saveAccession() {
      const store = useStore();
      const data = {
        accession_number: this.accession_number,
        date_received: this.date_received,
        source_of_fund: this.source_of_fund,
        cost_price: this.cost_price,
        remarks: this.remarks,
        isbn: this.isbn,
        dateaccession: this.dateaccession,
        title: this.title,
        author: this.author,
        edition: this.edition,
        volumes: this.volumes,
        pages: this.pages,
        copyright: this.copyright,
        publisher: this.publisher,
        department: this.department,
        copy: this.copy,
        encoder: this.encoder,
        type: this.type,
        publicationPlace: this.publicationPlace,
        call_no: this.call_no,
        id: this.accession_id
      };

      axios.post(`${store.appURL}/admin.php`, { save: JSON.stringify(data) }, { withCredentials: true })
        .then(response => {
          console.log('Accession saved:', response.data);
          this.fetchBooks();
          this.resetForm();
        })
        .catch(error => {
          console.error('Error saving accession:', error);
        });
    },
    deleteAccession() {
      const store = useStore();
      const id = this.accession_id;

      axios.post(`${store.appURL}/admin.php`, { delete: id }, { withCredentials: true })
        .then(response => {
          console.log('Accession deleted:', response.data);
          this.fetchBooks();
          this.resetForm();
        })
        .catch(error => {
          console.error('Error deleting accession:', error);
        });
    },
    searchAccession() {
      this.loadingAccessions = true;
      this.loadingAccessions = false;
    },
    populateForm(book) {
      this.accession_number = book.accession_number;
      this.date_received = book.date_received;
      this.source_of_fund = book.source_of_fund;
      this.cost_price = book.cost_price;
      this.remarks = book.remarks;
      this.isbn = book.isbn;
      this.dateaccession = book.dateaccession;
      this.title = book.title;
      this.author = book.author;
      this.edition = book.edition;
      this.volumes = book.volumes;
      this.pages = book.pages;
      this.copyright = book.copyright;
      this.publisher = book.publisher;
      this.department = book.department;
      this.copy = book.copy;
      this.encoder = book.encoder;
      this.type = book.type;
      this.publicationPlace = book.publicationPlace;
      this.call_no = book.call_no;
      this.accession_id = book.id;
    },
    resetForm() {
      this.accession_number = '';
      this.date_received = '';
      this.source_of_fund = '';
      this.cost_price = '';
      this.remarks = '';
      this.isbn = '';
      this.dateaccession = '';
      this.title = '';
      this.author = '';
      this.edition = '';
      this.volumes = '';
      this.pages = '';
      this.copyright = '';
      this.publisher = '';
      this.department = '';
      this.copy = '';
      this.encoder = '';
      this.type = '';
      this.publicationPlace = '';
      this.call_no = '';
      this.accession_id = null;
    }
  },

  watch: {
    accessionSearch(newSearch) {
      this.searchAccession();
    }
  },

  mounted() {
    this.fetchBooks();
  }
};
</script>
