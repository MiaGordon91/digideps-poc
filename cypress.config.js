import { defineConfig } from "cypress";

import pkg from 'cy-verify-downloads';
const {verifyDownloadTasks} = pkg;


export default defineConfig({
  e2e: {
    setupNodeEvents(on, config) {
      on('task', verifyDownloadTasks)
    },
  },
});
