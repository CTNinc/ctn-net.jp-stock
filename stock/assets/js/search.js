// let carData = {};

// const makerSelect = document.getElementById('maker-select');
// const vehicleSelect = document.getElementById('vehicle-select');
// const gradeSelect = document.getElementById('grade-select');

// const selectedMaker = document.getElementById('selected-maker')?.value || '';
// const selectedVehicle = document.getElementById('selected-vehicle')?.value || '';
// const selectedVersion = document.getElementById('selected-version')?.value || '';

// fetch('/cached_models.json')
//   .then(response => response.json())
//   .then(data => {
//     carData = data;

//     const makerSelect = document.getElementById('maker-select');
//     Object.keys(carData).forEach(maker => {
//       const option = document.createElement('option');
//       option.value = maker;
//       option.textContent = maker;
//       makerSelect.appendChild(option);
//     });
//   });



// let carData = {};

// const urlParams = new URLSearchParams(window.location.search);
// const selectedMaker = urlParams.get('maker') || '';
// const selectedVehicle = urlParams.get('vehicle') || '';
// const selectedYearMin = urlParams.get('year_min') || '';
// const selectedYearMax = urlParams.get('year_max') || '';

// const selectedkilometMin = urlParams.get('mileage_min') || '';
// const selectedkilometMax = urlParams.get('mileage_max') || '';
// const selectedpriceMin = urlParams.get('price_min') || '';
// const selectedpriceMax = urlParams.get('price_max') || '';

// const selectedBodyTypes = urlParams.get('bodyType') || '';


// fetch('cached_models.json')
//   .then(response => response.json())
//   .then(data => {
//     carData = data;
//     console.log("Car Data:", carData);

//     Object.keys(carData).forEach(maker => {
//       const option = document.createElement('option');
//       option.value = maker;
//       option.textContent = maker;
//       if (maker === selectedMaker) {
//         option.selected = true;
//       }
//       makerSelect.appendChild(option);
//       console.log("Selected maker:", selectedMaker);
//     });

//     if (selectedMaker && carData[selectedMaker]) {
//       vehicleSelect.disabled = false;
//       Object.keys(carData[selectedMaker]).forEach(vehicle => {
//         const option = document.createElement('option');
//         option.value = vehicle;
//         option.textContent = vehicle;
//         if (vehicle === selectedVehicle) {
//           option.selected = true;
//         }
//         vehicleSelect.appendChild(option);
//       });
//     }
//   });



//   makerSelect.addEventListener('change', function () {
//     const selectedMaker = this.value;
//     vehicleSelect.innerHTML = '<option value="">選択する</option>';

//     if (selectedMaker && carData[selectedMaker]) {
//       vehicleSelect.disabled = false;

//       Object.keys(carData[selectedMaker]).forEach(vehicle => {
//         const option = document.createElement('option');
//         option.value = vehicle;
//         option.textContent = vehicle;
//         vehicleSelect.appendChild(option);
//       });
//     } else {
//       vehicleSelect.disabled = true;
//     }
//   });


// fetch('cached_models.json')
//   .then(response => response.json())
//   .then(data => {
//     carData = data;

//     // Lặp qua từng form
//     document.querySelectorAll('.car-search-form').forEach(form => {
//       const makerSelect = form.querySelector('.maker-select');
//       const vehicleSelect = form.querySelector('.vehicle-select');

//       // Đổ Maker vào
//       Object.keys(carData).forEach(maker => {
//         const option = document.createElement('option');
//         option.value = maker;
//         option.textContent = maker;
//         if (maker === urlParams.get('maker')) {
//           option.selected = true;
//         }
//         makerSelect.appendChild(option);
//       });

//       const selectedMaker = urlParams.get('maker');
//       const selectedVehicle = urlParams.get('vehicle');

//       if (selectedMaker && carData[selectedMaker]) {
//         vehicleSelect.disabled = false;
//         Object.keys(carData[selectedMaker]).forEach(vehicle => {
//           const option = document.createElement('option');
//           option.value = vehicle;
//           option.textContent = vehicle;
//           if (vehicle === selectedVehicle) {
//             option.selected = true;
//           }
//           vehicleSelect.appendChild(option);
//         });
//       }

//       // Gắn sự kiện change cho từng maker select
//       makerSelect.addEventListener('change', function () {
//         const selected = this.value;
//         vehicleSelect.innerHTML = '<option value="">選択する</option>';
//         vehicleSelect.disabled = true;

//         if (selected && carData[selected]) {
//           vehicleSelect.disabled = false;
//           Object.keys(carData[selected]).forEach(vehicle => {
//             const option = document.createElement('option');
//             option.value = vehicle;
//             option.textContent = vehicle;
//             vehicleSelect.appendChild(option);
//           });
//         }
//       });
//     });
//   });


//   function restoreSelectValue(selectId, paramKey) {
//     const value = urlParams.get(paramKey);
//     if (!value) return;

//     const select = document.getElementById(selectId);
//     if (!select) return;

//     Array.from(select.options).forEach(option => {
//       if (option.value === value) {
//         option.selected = true;
//       }
//     });
//   }

// restoreSelectValue('year-min', 'year_min');
// restoreSelectValue('year-max', 'year_max');

// restoreSelectValue('mileage-min', 'mileage_min');
// restoreSelectValue('mileage-max', 'mileage_max');

// restoreSelectValue('price-min', 'price_min');
// restoreSelectValue('price-max', 'price_max');



let carData = {};
const urlParams = new URLSearchParams(window.location.search);

fetch('cached_models.json')
  .then(response => response.json())
  .then(data => {
    carData = data;

    document.querySelectorAll('.car-search-form').forEach(form => {
      const makerSelect = form.querySelector('.maker-select');
      const vehicleSelect = form.querySelector('.vehicle-select');

      // 1. Đổ maker
      Object.keys(carData).forEach(maker => {
        const option = document.createElement('option');
        option.value = maker;
        option.textContent = maker;
        if (maker === urlParams.get('maker')) {
          option.selected = true;
        }
        makerSelect.appendChild(option);
      });

      // 2. Đổ vehicle theo maker
      const selectedMaker = urlParams.get('maker');
      const selectedVehicle = urlParams.get('vehicle');
      if (selectedMaker && carData[selectedMaker]) {
        vehicleSelect.disabled = false;
        Object.keys(carData[selectedMaker]).forEach(vehicle => {
          const option = document.createElement('option');
          option.value = vehicle;
          option.textContent = vehicle;
          if (vehicle === selectedVehicle) {
            option.selected = true;
          }
          vehicleSelect.appendChild(option);
        });
      }

      // 3. Sự kiện đổi maker
      makerSelect.addEventListener('change', function () {
        const selected = this.value;
        vehicleSelect.innerHTML = '<option value="">選択する</option>';
        vehicleSelect.disabled = true;

        if (selected && carData[selected]) {
          vehicleSelect.disabled = false;
          Object.keys(carData[selected]).forEach(vehicle => {
            const option = document.createElement('option');
            option.value = vehicle;
            option.textContent = vehicle;
            vehicleSelect.appendChild(option);
          });
        }
      });

      // 4. Restore các select khác (year, mileage, price...) trong form hiện tại
      const restoreFields = [
        ['year-min', 'year_min'],
        ['year-max', 'year_max'],
        ['mileage-min', 'mileage_min'],
        ['mileage-max', 'mileage_max'],
        ['price-min', 'price_min'],
        ['price-max', 'price_max'],
      ];

      restoreFields.forEach(([className, paramKey]) => {
        const value = urlParams.get(paramKey);
        if (!value) return;

        const select = form.querySelector(`.${className}`);
        if (!select) return;

        Array.from(select.options).forEach(option => {
          if (option.value === value) {
            option.selected = true;
          }
        });
      });
    });
  });
