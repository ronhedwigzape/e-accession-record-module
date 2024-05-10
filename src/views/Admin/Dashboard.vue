<template>
  <TopNavigationBar/>
  <v-container fluid>
    <v-row>
      <v-col cols="6">
        <v-card>
          <v-card-title>Book Details</v-card-title>
          <v-card-text>
            <v-form>
              <v-row>
                <v-col cols="6">
                  <v-text-field label="Accession Number" outlined dense></v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-text-field label="Date Received" outlined dense></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="6">
                  <v-text-field label="No. of Copies" outlined dense></v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-text-field label="Year" outlined dense></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12">
                  <v-text-field label="Title" outlined dense></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12">
                  <v-text-field label="Author" outlined dense></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12">
                  <v-text-field label="Publisher" outlined dense></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" class="d-flex justify-space-between">
                  <v-btn color="primary">Save</v-btn>
                  <v-btn color="error">Delete</v-btn>
                  <v-btn>Reset</v-btn>
                </v-col>
              </v-row>
            </v-form>
          </v-card-text>
        </v-card>
        <v-card>
          <v-card-title>Print</v-card-title>
          <v-card-text>
            <v-form>
              <v-radio-group row>
                <v-radio label="Book Details" value="book"></v-radio>
                <v-radio label="User Manual" value="um"></v-radio>
              </v-radio-group>
              <v-btn color="primary">Generate</v-btn>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="6">
        <v-card>
          <v-card-title>List of Books</v-card-title>
          <v-card-text>
            <v-text-field label="Search" outlined dense solo-inverted clearable append-icon="mdi-magnify"></v-text-field>
            <v-data-table
              :headers="headers"
              :items="books"
              :search="search"
              class="elevation-1"
            ></v-data-table>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import TopNavigationBar from "@/components/navbars/TopNavigationBar.vue";

export default {
  components: {TopNavigationBar},
  data: () => ({
    search: '',
    books: [
      { accession: '0201410', date: '2019-04-11', author: 'Pratt Philip J.', title: 'Database Management System', publisher: 'McGraw Hill', year: '2018' },
      { accession: '0201411', date: '2020-06-15', author: 'Elmasri Navathe', title: 'Fundamentals of Database Systems', publisher: 'Pearson', year: '2016' },
      { accession: '0201412', date: '2021-03-22', author: 'Silberschatz A.', title: 'Operating System Concepts', publisher: 'Wiley', year: '2018' },
      { accession: '0201413', date: '2018-07-08', author: 'Tanenbaum A.S.', title: 'Computer Networks', publisher: 'Prentice Hall', year: '2017' },
      { accession: '0201414', date: '2019-11-19', author: 'Kernighan B.', title: 'The C Programming Language', publisher: 'PHI', year: '1988' },
      { accession: '0201415', date: '2020-01-27', author: 'Martin R.C.', title: 'Clean Code', publisher: 'Prentice Hall', year: '2009' },
      { accession: '0201416', date: '2021-05-14', author: 'Harel D.', title: 'Algorithmics', publisher: 'Addison Wesley', year: '2004' },
      { accession: '0201417', date: '2017-02-11', author: 'Fowler M.', title: 'Refactoring', publisher: 'Addison Wesley', year: '2018' },
      { accession: '0201418', date: '2018-09-20', author: 'Beck K.', title: 'Test Driven Development', publisher: 'Addison Wesley', year: '2003' },
      { accession: '0201419', date: '2019-12-15', author: 'Gamma E.', title: 'Design Patterns', publisher: 'Addison Wesley', year: '1995' },
      { accession: '0201420', date: '2020-08-05', author: 'Knuth D.E.', title: 'The Art of Computer Programming', publisher: 'Addison Wesley', year: '1997' },
      { accession: '0201450', date: '2019-04-11', author: 'Tanya X. Short; Tarn Adams', title: 'Procedural Generation in Game Design', publisher: 'CRC Press', year: '2017' },
      { accession: '0201451', date: '2019-04-11', author: 'Donovan, John; Prabhu, Krish', title: 'Building the Network for the Future', publisher: 'Springer', year: '2019' },
      { accession: '0201452', date: '2019-04-11', author: 'Zhang, Dan', title: 'AI for program data quality in cooperative wireless networks', publisher: 'IEEE', year: '2020' },
      { accession: '0201453', date: '2019-04-11', author: 'Hassan, Emad S.', title: 'Security and data reliability in cooperative cognitive radio networks', publisher: 'IEEE', year: '2021' },
      { accession: '0201454', date: '2019-04-11', author: 'Bone, James', title: 'Cognitive hack: the new battleground in cybersecurity... the human mind', publisher: 'CRC Press', year: '2018' },
      { accession: '0201455', date: '2019-04-11', author: 'Deka, Ganesh Chandra', title: 'NoSQL database for storage and retrieval of data in cloud', publisher: 'CRC Press', year: '2019' },
      { accession: '0201456', date: '2019-04-11', author: 'Boss, Stephen', title: 'Digital type design for branding: designing letters from their source', publisher: 'CRC Press', year: '2020' },
      { accession: '0201457', date: '2019-04-11', author: 'Carman, Christopher', title: 'Visual design concepts for mobile games', publisher: 'CRC Press', year: '2021' }
    ],
    headers: [
      { title: 'Accession Number', value: 'accession', align: 'start', sortable: true },
      { title: 'Date Received', value: 'date', align: 'start', sortable: true },
      { title: 'Author', value: 'author', align: 'start', sortable: true },
      { title: 'Title', value: 'title', align: 'start', sortable: true },
      { title: 'Publisher', value: 'publisher', align: 'start', sortable: true },
      { title: 'Year', value: 'year', align: 'start', sortable: true }
    ],
  }),
}
</script>
