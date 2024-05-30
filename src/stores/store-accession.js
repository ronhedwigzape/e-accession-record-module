import { defineStore } from 'pinia';
import $ from 'jquery';
import { useStore } from '@/stores/store';

export const useAccessionStore = defineStore('accession', {
  state: () => ({
    model: null,
    bookSearch: '',
    accessionSearch: '',
    itemsPerPage: 10,
    loadingBooks: false,
    loadingAccessions: false,
    snackbar: false,
    snackbarMessage: '',
    snackbarColor: '',
    departments: [
      { name: 'CAS', description: 'College of Arts and Sciences' },
      { name: 'CTDE', description: 'College of Technological and Developmental Education' },
      { name: 'CCS', description: 'College of Computer Studies' },
      { name: 'CEA', description: 'College of Engineering and Architecture' },
      { name: 'CHS', description: 'College of Health and Sciences' },
      { name: 'CTHBM', description: 'College of Tourism, Hospitality, and Business Management' },
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
      { title: 'Date Accessioned', value: 'dateaccession', align: 'start', sortable: true },
      { title: 'Volume', value: 'volumes', align: 'start', sortable: true },
      { title: 'ISBN', value: 'isbn', align: 'start', sortable: true },
      { title: 'Author', value: 'author', align: 'start', sortable: true },
      { title: 'Title', value: 'title', align: 'start', sortable: true },
      { title: 'Edition', value: 'edition', align: 'start', sortable: true },
      { title: 'Pages', value: 'pages', align: 'start', sortable: true },
      { title: 'Copyright', value: 'copyright', align: 'start', sortable: true },
      { title: 'Publisher', value: 'publisher', align: 'start', sortable: true },
      { title: 'Place of Publication', value: 'publicationPlace', align: 'start', sortable: true },
      { title: 'Department', value: 'department', align: 'start', sortable: true },
      { title: 'Type', value: 'type', align: 'start', sortable: true },
      { title: 'Cost Price', value: 'cost_price', align: 'start', sortable: true },
      { title: 'Source of Fund', value: 'source_of_fund', align: 'start', sortable: true },
      { title: 'Remarks', value: 'remarks', align: 'start', sortable: true }
    ],
    accession_number: '',
    date_received: null,
    source_of_fund: '',
    cost_price: '',
    remarks: '',
    isbn: '',
    dateaccession: null,
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
        const title = book.title ? book.title.toLowerCase() : '';
        const author = book.author ? book.author.toLowerCase() : '';
        const search = state.bookSearch ? state.bookSearch.toLowerCase() : '';
        return title.includes(search) || author.includes(search);
      });
    },
    filteredAccessions(state) {
      return state.books.filter(book => {
        const accessionNumber = book.accession_number ? book.accession_number.toLowerCase() : '';
        const title = book.title ? book.title.toLowerCase() : '';
        const author = book.author ? book.author.toLowerCase() : '';
        const search = state.accessionSearch ? state.accessionSearch.toLowerCase() : '';
        return accessionNumber.includes(search) || title.includes(search) || author.includes(search);
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
      $.ajax({
        url: `${useStore().appURL}/admin.php`,
        type: 'GET',
        xhrFields: {
          withCredentials: true
        },
        data: {
          load: ''
        },
        success: (response) => {
          const books = JSON.parse(response);
          this.books = books.map(book => ({
            ...book,
            date_received: this.parseDate(book.date_received),
            dateaccession: this.parseDate(book.dateaccession)
          }));
        },
        error: (error) => {
          console.error('Error fetching books:', error);
        },
        complete: () => {
          this.loadingBooks = false;
        }
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
        department: this.department.name,
        copy: this.copy,
        encoder: this.encoder,
        type: this.type,
        publicationPlace: this.publicationPlace,
        call_no: this.call_no,
        id: this.accession_id
      };

      console.log('Saving accession with data:', data);

      $.ajax({
        url: `${useStore().appURL}/admin.php`,
        type: 'POST',
        xhrFields: {
          withCredentials: true
        },
        data: {
          save: JSON.stringify(data)
        },
        success: (response) => {
          console.log('Accession saved:', response);
          this.snackbarMessage = 'Accession record saved successfully.';
          this.snackbarColor = 'success';
          this.snackbar = true;
          this.fetchBooks();
          this.resetForm();
        },
        error: (error) => {
          console.error('Error saving accession:', error);
          this.snackbarMessage = 'Failed to save accession record.';
          this.snackbarColor = 'error';
          this.snackbar = true;
          if (error.responseJSON) {
            console.error('Response data:', error.responseJSON);
          } else if (error.responseText) {
            console.error('Response text:', error.responseText);
          } else {
            console.error('Error message:', error.message);
          }
        }
      });
    },
    deleteAccession() {
      const id = this.accession_id;

      $.ajax({
        url: `${useStore().appURL}/admin.php`,
        type: 'POST',
        xhrFields: {
          withCredentials: true
        },
        data: {
          delete: id
        },
        success: (response) => {
          console.log('Accession deleted:', response);
          this.snackbarMessage = 'Accession record deleted successfully.';
          this.snackbarColor = 'success';
          this.snackbar = true;
          this.fetchBooks();
          this.resetForm();
        },
        error: (error) => {
          console.error('Error deleting accession:', error);
          this.snackbarMessage = 'Failed to delete accession record.';
          this.snackbarColor = 'error';
          this.snackbar = true;
        }
      });
    },
    populateForm(book) {
      this.accession_number = book.accession_number;
      this.date_received = this.parseDate(book.date_received);
      this.source_of_fund = book.source_of_fund;
      this.cost_price = book.cost_price;
      this.remarks = book.remarks;
      this.isbn = book.isbn;
      this.dateaccession = this.parseDate(book.dateaccession);
      this.title = book.title;
      this.author = book.author;
      this.edition = book.edition;
      this.volumes = book.volumes;
      this.pages = book.pages;
      this.copyright = book.copyright;
      this.publisher = book.publisher;
      this.department = this.departments.find(dept => dept.name === book.department) || { name: book.department, description: '' };
      this.copy = book.copy;
      this.encoder = book.encoder;
      this.type = book.type;
      this.publicationPlace = book.publicationPlace;
      this.call_no = book.call_no;
      this.accession_id = book.id;
    },
    parseDate(dateString) {
      if (!dateString) return null;
      // Remove trailing period if present
      dateString = dateString.replace(/\.$/, '');
      const date = new Date(dateString);
      if (isNaN(date.getTime())) {
        // Handle date string without time part
        const parts = dateString.split('-');
        if (parts.length === 3) {
          return new Date(parts[0], parts[1] - 1, parts[2]);
        }
        return null;
      }
      // Format the date to YYYY-MM-DD
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    },
    resetForm() {
      this.accession_number = '';
      this.date_received = null;
      this.source_of_fund = '';
      this.cost_price = '';
      this.remarks = '';
      this.isbn = '';
      this.dateaccession = null;
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
