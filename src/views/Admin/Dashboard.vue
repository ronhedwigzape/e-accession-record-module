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
              <v-col cols="12" md="7">
                <v-card elevation="0">
                  <v-card-text>
                    <v-row>
                      <v-col cols="6">
                        <v-date-input
                          v-model="useAccessionStore().dateaccession"
                          label="Date Accessioned"
                          variant="outlined"
                          density="compact">
                        </v-date-input>
                      </v-col>
                      <v-col cols="6">
                        <v-text-field
                          v-model="useAccessionStore().accession_number"
                          @input="updateType"
                          label="Accession Number"
                          variant="outlined"
                          density="compact"
                          hint="e.g. 006201AV - For AV"
                        >
                        </v-text-field>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="6">
                        <v-text-field
                          v-model="useAccessionStore().copy"
                          label="No. of Copies"
                          variant="outlined"
                          density="compact"
                        />
                      </v-col>
                      <v-col cols="6">
                        <v-radio-group v-model="useAccessionStore().type" density="compact" inline>
                          <v-radio label="Book" value="BK"></v-radio>
                          <v-radio label="UM" value="UM"></v-radio>
                          <v-radio label="AV" value="AV"></v-radio>
                        </v-radio-group>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12">
                        <v-text-field
                          v-model="useAccessionStore().title"
                          label="Title"
                          variant="outlined"
                          density="compact"
                        />
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12">
                        <v-text-field
                          v-model="useAccessionStore().author"
                          label="Author"
                          variant="outlined"
                          density="compact"
                        />
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="6">
                        <v-text-field
                          v-model="useAccessionStore().publisher"
                          label="Publisher"
                          variant="outlined"
                          density="compact"
                        />
                      </v-col>
                      <v-col cols="6">
                        <v-text-field
                          v-model="useAccessionStore().publicationPlace"
                          label="Place of Publication"
                          variant="outlined"
                          density="compact"
                        />
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="4">
                        <v-text-field
                          v-model="useAccessionStore().volumes"
                          label="Volume"
                          variant="outlined"
                          density="compact"
                          hint="e.g. 1 of 3"
                        />
                      </v-col>
                      <v-col cols="8">
                        <v-text-field
                          v-model="useAccessionStore().isbn"
                          label="ISBN"
                          variant="outlined"
                          density="compact"
                          hint="e.g. 9789814732109"
                        />
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="3">
                        <v-text-field
                          v-model="useAccessionStore().copyright"
                          label="Copyright" variant="outlined"
                          density="compact"
                          hint="e.g. Cengag"
                        />
                      </v-col>
                      <v-col cols="3">
                        <v-text-field
                          v-model="useAccessionStore().edition"
                          label="Edition"
                          variant="outlined"
                          density="compact"
                          hint="e.g. 1st"
                        />
                      </v-col>
                      <v-col cols="3">
                        <v-text-field
                          v-model="useAccessionStore().pages"
                          label="Pages"
                          variant="outlined"
                          density="compact"
                          hint="e.g. 500"
                        />
                      </v-col>
                      <v-col cols="3">
                        <v-text-field
                          v-model="useAccessionStore().cost_price"
                          label="Cost Price"
                          variant="outlined"
                          density="compact"
                          hint="e.g. 30.00"
                        />
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="6">
                        <v-text-field
                          v-model="useAccessionStore().source_of_fund"
                          label="Source of Fund"
                          variant="outlined"
                          density="compact"
                          hint="e.g. Library Fund"
                        />
                      </v-col>
                      <v-col cols="6">
                        <v-select
                          v-model="useAccessionStore().department"
                          clearable
                          label="Department"
                          :items="useAccessionStore().departments"
                          :item-props="useAccessionStore().departmentProps"
                          variant="outlined"
                          density="compact"
                        ></v-select>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12">
                        <v-text-field
                          v-model="useAccessionStore().remarks"
                          label="Remarks"
                          variant="outlined"
                          density="compact"
                        />
                      </v-col>
                    </v-row>
                  </v-card-text>
                  <v-card-actions>
                    <v-btn variant="tonal" @click="useAccessionStore().resetForm">Reset</v-btn>
                    <v-btn variant="tonal" color="primary" @click="useAccessionStore().saveAccession">Save</v-btn>
                    <v-btn variant="tonal" color="error" @click="useAccessionStore().deleteAccession">Delete</v-btn>
                    <!--                    <v-btn variant="tonal" color="secondary" @click="fillSampleData">Fill Sample Data</v-btn>-->
                  </v-card-actions>
                </v-card>
              </v-col>
              <v-col cols="12" md="5">
                <v-card variant="tonal" color="primary" height="1000" max-height="900" elevation="0">
                  <v-card-text>
                    <v-card variant="text">
                      <v-card-title>Accession to Edit</v-card-title>
                      <v-card-text>
                        <v-row>
                          <v-col cols="12">
                            <v-text-field
                              v-model="useAccessionStore().accessionSearch"
                              hint="Press Enter to Search"
                              variant="outlined"
                              density="compact"
                            />
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
                                v-for="(book, index) in useAccessionStore().filteredAccessions"
                                :key="book.id"
                                @click="useAccessionStore().populateForm(book)"
                              >
                                <v-list-item-title>{{ book.accession_number }} - {{ book.title }}</v-list-item-title>
                                <v-list-item-subtitle>{{ book.author }}</v-list-item-subtitle>
                              </v-list-item>
                            </v-list>
                            <v-progress-circular
                              v-if="useAccessionStore().loadingAccessions"
                              indeterminate
                              color="black"
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
                  <v-radio-group v-model="useAccessionStore().type" inline>
                    <v-radio label="Book" value="BK"></v-radio>
                    <v-radio label="AV" value="AV"></v-radio>
                    <v-radio label="UM" value="UM"></v-radio>
                  </v-radio-group>
                  <v-radio-group>
                    <v-radio label="Bibliographies" value="bibliographies"></v-radio>
                    <v-radio label="Materials for Circulation" value="circulation"></v-radio>
                  </v-radio-group>
                </v-col>
                <v-col cols="12" md="6">
                  <v-select
                    v-model="useAccessionStore().department"
                    clearable
                    label="Department"
                    :items="useAccessionStore().departments"
                    :item-props="useAccessionStore().departmentProps"
                    variant="outlined"
                    density="compact"
                  ></v-select>
                  <div class="d-flex justify-center">
                    <v-date-input
                      variant="outlined"
                      v-model="useAccessionStore().model"
                      label="Select range (From - To)"
                      max-width="368"
                      multiple="range"
                      density="compact"
                    ></v-date-input>
                  </div>
                </v-col>
              </v-row>
              <v-btn variant="tonal" color="primary" @click="useAccessionStore().generateReport">Generate</v-btn>
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
            <v-text-field
              v-model="useAccessionStore().bookSearch"
              variant="outlined"
              label="Search"
              clearable
              append-icon="mdi-magnify"
            />
            <v-data-table
              v-model:items-per-page="useAccessionStore().itemsPerPage"
              :headers="useAccessionStore().headers"
              :items="useAccessionStore().filteredBooks"
              :search="useAccessionStore().bookSearch"
              class="elevation-1"
            ></v-data-table>
            <v-row v-if="useAccessionStore().loadingBooks"
                   class="d-flex flex-column justify-center align-center text-center mt-4">
              <v-col>
                <v-progress-circular
                  indeterminate
                  color="primary"
                ></v-progress-circular>
              </v-col>
              <v-col>
                <p>Loading...</p>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <v-snackbar
      v-model="useAccessionStore().snackbar"
      :color="useAccessionStore().snackbarColor"
      timeout="3000"
      top
    >
      {{ useAccessionStore().snackbarMessage }}
    </v-snackbar>
  </v-container>
</template>

<script setup>
import { useAccessionStore } from '@/stores/store-accession';
import TopNavigationBar from "@/components/navbars/TopNavigationBar.vue";
import { onMounted } from "vue";

onMounted(() => {
  useAccessionStore().fetchBooks();
});

function updateType() {
  if (useAccessionStore().accession_number.endsWith('AV')) {
    useAccessionStore().type = 'AV';
  } else if (useAccessionStore().accession_number.endsWith('UM')) {
    useAccessionStore().type = 'UM';
  } else {
    useAccessionStore().type = 'BK';
  }
}

function fillSampleData() {
  const store = useAccessionStore();
  store.accession_number = '006201AV';
  store.date_received = new Date();
  store.source_of_fund = 'Library Fund';
  store.cost_price = '30.00';
  store.remarks = 'Sample remarks';
  store.isbn = '9789814732109';
  store.dateaccession = new Date();
  store.title = 'Sample Book Title';
  store.author = 'Sample Author';
  store.edition = '1st';
  store.volumes = '1 of 3';
  store.pages = '500';
  store.copyright = '2024';
  store.publisher = 'Sample Publisher';
  store.department = store.departments.find(dept => dept.name === 'CAS');
  store.copy = '1';
  store.encoder = 'Sample Encoder';
  store.type = 'AV';
  store.publicationPlace = 'Sample Place';
  store.call_no = '123.456';
}
</script>

