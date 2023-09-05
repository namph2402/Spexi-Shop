import * as FileSaver from 'file-saver';

function exportXLSXFile(data, filename) {
  const blobValue = new Blob([data['body']], {
    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
  });
  FileSaver.saveAs(blobValue, filename + '.xlsx');
}

export const ExcelHelper = {
  exportXLSXFile
};
