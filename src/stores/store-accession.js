import { defineStore } from 'pinia';
import axios from 'axios';
import { useStore } from '@/stores/store';

export const useAccessionStore = defineStore('accession', {
  state: () => ({
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
    date_received: null,
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

  getters: {
    filteredBooks(state) {
      return state.books.filter(book => {
        return book.title.toLowerCase().includes(state.bookSearch.toLowerCase()) ||
          book.author.toLowerCase().includes(state.bookSearch.toLowerCase());
      });
    },
    filteredAccessions(state) {
      return state.books.filter(book => {
        return book.accession_number.toLowerCase().includes(state.accessionSearch.toLowerCase()) ||
          book.title.toLowerCase().includes(state.accessionSearch.toLowerCase()) ||
          book.author.toLowerCase().includes(state.accessionSearch.toLowerCase());
      });
    }
  },

  actions: {
    departmentProps(item) {
      return {
        title: item.name,
        subtitle: item.description,
      };
    },
    fetchBooks() {
      this.loadingBooks = true;
      axios.get(`${useStore().appURL}/admin.php?load`, { withCredentials: true })
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

      axios.post(`${useStore().appURL}/admin.php`, { save: JSON.stringify(data) }, { withCredentials: true })
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
      const id = this.accession_id;

      axios.post(`${useStore().appURL}/admin.php`, { delete: id }, { withCredentials: true })
        .then(response => {
          console.log('Accession deleted:', response.data);
          this.fetchBooks();
          this.resetForm();
        })
        .catch(error => {
          console.error('Error deleting accession:', error);
        });
    },
    populateForm(book) {
      this.accession_number = book.accession_number;
      this.date_received = new Date(book.date_received);
      if (isNaN(this.date_received.getTime())) {
        this.date_received = null;
      }
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
  }
});
