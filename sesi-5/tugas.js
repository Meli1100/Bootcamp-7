    // Data Produk (Array JS)
    const products = [
        {
            nama: "MacBook Pro M4",
            deskripsi: "Laptop canggih performa tinggi",
            harga: 34000000,
            kategori: "Elektronik",
            gambar: "https://macstore.id/wp-content/uploads/2024/11/mbp14-silver-gallery1-202410.jpeg"
        },
        {
            nama: "Laptop HP Pavilion Gaming 15",
            deskripsi: "Laptop gaming dengan performa tangguh",
            harga: 15000000,
            kategori: "Elektronik",
            gambar: "https://www.static-src.com/wcsstore/Indraprastha/images/catalog/full/catalog-image/109/MTA-163294687/hp_hp-pavilion-gaming-15-rtx3050-amd-reyzen5-5600h-8gb-512gb-win10-office_full01.jpg"
        },
        {
            nama: "iPhone 16 Pro Max",
            deskripsi: "Smartphone flagship terbaru dari Apple",
            harga: 20000000,
            kategori: "Elektronik",
            gambar: "https://www.digimap.co.id/cdn/shop/files/0788-APPMYX23ID-A-1.jpg?v=17340678690"
        },
        {
            nama: "Kaos Polos",
            deskripsi: "Kaos polos berkualitas tinggi",
            harga: 100000,
            kategori: "Fashion",
            gambar: "https://dynamic.zacdn.com/0oVCccVdjOc63nZ9Vnw7GXtrb90=/filters:quality(70):format(webp)/https://static-id.zacdn.com/p/simple-perfect-7513-1433673-1.jpg"
        },
        {
            nama: "Sepatu Sneakers",
            deskripsi: "Sepatu sneakers stylish dan nyaman",
            harga: 800000,
            kategori: "Fashion",
            gambar: "https://sc04.alicdn.com/kf/H46d649646fe14f65a067c2fd971abdacF.jpg"
        },
        {
            nama: "Cokelat Premium",
            deskripsi: "Cokelat premium kualitas terbaik",
            harga: 100000,
            kategori: "Makanan",
            gambar: "https://images-cdn.ubuy.co.id/65c1535da231074c8f5e17a8-ferrero-rocher-premium-chocolate-bars.jpg"
        },
        {
            nama: "Kopi Arabika",
            deskripsi: "Kopi Arabika dengan cita rasa khas",
            harga: 90000,
            kategori: "Makanan",
            gambar: "https://imgx.sonora.id/crop/0x0:0x0/x/photo/2021/10/19/kopijpg-20211019081928.jpg"
        }
    ];

    const productList = document.getElementById("productList");
    const searchInput = document.getElementById("searchInput");
    const filterKategori = document.getElementById("filterKatergori");
    const sortHarga = document.getElementById("sortHarga");

    function tampilkanProduk(data) {
        productList.innerHTML = "";

        if (data.length === 0) {
            productList.innerHTML = "<p class='text-center'>Produk tidak ditemukan.</p>";
            return;
        }

        data.forEach(produk => {
            productList.innerHTML += `
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="${produk.gambar}" class="card-img-top" alt="${produk.nama}">
                        <div class="card-body">
                            <h5 class="card-title">${produk.nama}</h5>
                            <p class="card-text">${produk.deskripsi}</p>
                            <p class="fw-bold text-primary">Rp ${produk.harga.toLocaleString()}</p>
                            <span class="badge bg-secondary">${produk.kategori}</span>
                        </div>
                    </div>
                </div>
            `;  
        });
    }

    function filterDanSort() {
        let hasil = [...products];

        // Filter Kategori
        if (filterKategori.value) {
            hasil = hasil.filter(p => p.kategori === filterKategori.value);
        }

        // Search Nama 
        if (searchInput.value) {
            hasil = hasil.filter(p => p.nama.toLowerCase().includes(searchInput.value.toLowerCase()));
        }

        // Sort Harga
        if (sortHarga.value === "tinggi") {
            hasil.sort((a, b) => b.harga - a.harga);
        } else if (sortHarga.value === "rendah") {
            hasil.sort((a, b) => a.harga - b.harga);
        }

        tampilkanProduk(hasil);
    }

    // Event
    searchInput.addEventListener("input", filterDanSort);
    filterKategori.addEventListener("change", filterDanSort);
    sortHarga.addEventListener("change", filterDanSort);

    // Load Awal
    tampilkanProduk(products);