const table = document.querySelector("table");
const file_input = document.getElementById("images");
const submit_btn = document.getElementById("submit-btn");

let accepted_formats = [];
let formats = {};
fetch('data/formats.json')
  .then(response => response.json())
  .then(data => {
    accepted_formats = data.allowed_formats;
    formats = data.quality_formats;
  })
  .catch(error => console.error("Error fetching JSON:", error));

file_input.addEventListener("change", handleFileInputChange);

/**
 * Create table with file info.
 */
function handleFileInputChange() {
  table.innerHTML = "";
  for (let i = 0; i < this.files.length; i++) {
    const file = this.files[i];
    const file_info_tr = document.createElement("tr");
    const file_info_name = `
      <td class="file-info__name">
        <div class="convert-selection">
          <svg><use href="assets/sprite.svg#picture"></use></svg>
          ${file.name}
        </div>
      </td>`;
    const file_info_format = `
      <td class="file-info__format">
        <div class="convert-selection">
          <p class="small-text">convert to</p>
          <select name="format-${i}" id="file-info__format-${i}" required>
            <option value="">---</option>
          </select>
        </div>
      </td>`;
    const file_info_quality = `
      <td class="file-info__quality" id="file-info__quality-div-${i}" style="opacity: 0">
        <div class="convert-selection">
          <p>quality</p>
          <input type="number" name="quality-${i}" min="0" id="file-info__quality-div-${i}__input">
        </div>
      </td>`;
    const file_info_size = `
      <td class="file-info__size small-text">
        ${parseInt(file.size / 1024)} kB
      </td>`;
    file_info_tr.innerHTML = file_info_name + file_info_format + file_info_quality + file_info_size;
    table.appendChild(file_info_tr);
    const file_info_format_select = document.getElementById(`file-info__format-${i}`);
    const extension = getExtension(file.name);
    for (const format of accepted_formats[extension]) {
      const option = document.createElement("option");
      option.value = format;
      option.textContent = format.toUpperCase();
      file_info_format_select.appendChild(option);
    }
    file_info_format_select.addEventListener("change", function () {
      updateQualityOptions(this.value, i);
    });
    submit_btn.style.display = "block";
  }
}


/**
 * Update quality options based on format
 * @param {int} format Extension of the image
 * @param {int} i Index of the file
 */
function updateQualityOptions(format, i) {
  const quality_div = document.getElementById(`file-info__quality-div-${i}`);
  const quality_input = document.getElementById(`file-info__quality-div-${i}__input`);
  if (formats.hasOwnProperty(format)) {
    quality_input.value = formats[format].defaultQuality;
    quality_input.max = formats[format].maxQuality;
    quality_div.style.opacity = "1";
  } else {
    quality_div.style.opacity = "0";
  }
}

/**
 * Get extension of the file
 * @param {string} filename Name of the file
 * @returns {string} Extension of the file
 */
function getExtension(filename) {
  return filename.split('.').pop();
}
