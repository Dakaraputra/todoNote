<?php
require '../koneksi.php'; 
if (!$conn) {
    die("Koneksi gagal: " . pg_last_error());
}

//Ambil ID
$id = $_GET['id'] ?? $_POST['id'] ?? null;

// Jika ID kosong, balik ke index
if (!$id) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $contents = $_POST['contents']; 

    // Cek apakah ada gambar baru
    if (!empty($_FILES['note_image']['name'])) {
        $targetDir = "../uploads/"; 
        $fileName = time() . "_" . $_FILES['note_image']['name'];
        $uploadPath = $targetDir . $fileName; 
        $dbPath = "uploads/" . $fileName;     

        if (move_uploaded_file($_FILES['note_image']['tmp_name'], $uploadPath)) {
            // Update Judul, Isi, Gambar
            $sql = "UPDATE notes SET title = $1, contents = $2, image_path = $3 WHERE id = $4";
            $result = pg_query_params($conn, $sql, [$title, $contents, $dbPath, $id]);
        }
    } else {
        // Update Judul dan Isi saja
        $sql = "UPDATE notes SET title = $1, contents = $2 WHERE id = $3";
        $result = pg_query_params($conn, $sql, [$title, $contents, $id]);
    }

    if ($result) {
        header("Location: ../index.php");
        exit;
    } else {
        echo "Error Update: " . pg_last_error($conn);
    }
}

//ambil data lama
$sql = "SELECT * FROM notes WHERE id = $1";
$result = pg_query_params($conn, $sql, [$id]);
$note = pg_fetch_assoc($result);

//JIKA id tidak ada 
if (!$note) {
    die("<h3>Error: Data Note tidak ditemukan!</h3>
         <p>Anda mungkin mengklik tombol Edit pada 'Todo List' tapi diarahkan ke 'Update Note'.<br>
         Pastikan link di index.php sudah benar.</p>
         <a href='../index.php'>Kembali</a>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Note</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Poppins', 'sans-serif'],
                },
                colors: {
                    primary: '#4F46E5', // Indigo 600
                }
            }
        }
    }
    </script>
</head>
<body class="bg-gray-50 text-slate-800 font-sans antialiased min-h-screen flex items-center justify-center py-10 px-4">

    <?php if (!$note): ?>
        <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md border border-red-100 text-center">
            <div class="text-red-500 text-5xl mb-4">⚠️</div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Data Note Tidak Ditemukan!</h3>
            <p class="text-gray-600 mb-6 text-sm">
                Anda mungkin mengklik tombol Edit yang salah atau data telah dihapus.
            </p>
            <a href="../index.php" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">
                Kembali ke Home
            </a>
        </div>
        <?php exit; ?>
    <?php endif; ?>

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-lg border border-gray-100">
        
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-slate-700 flex items-center justify-center gap-2">
                Update Note
            </h2>
        </div>

        <form action="" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
            <input type="hidden" name="id" value="<?php echo $note['id']; ?>">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Catatan</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($note['title']); ?>" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition shadow-sm font-semibold text-gray-700">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Isi Catatan</label>
                <textarea name="contents" rows="5" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition shadow-sm resize-y leading-relaxed"><?php echo htmlspecialchars($note['contents']); ?></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini</label>
                <?php if ($note['image_path']): ?>
                    <div class="relative w-full h-48 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 group">
                        <img src="../uploads/<?php echo htmlspecialchars(basename($note['image_path'])); ?>" 
                             alt="Current Note Image" 
                             class="w-full h-full object-cover">
                    </div>
                <?php else: ?>
                    <div class="w-full h-20 bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-400 text-xs">
                        Tidak ada gambar terlampir
                    </div>
                <?php endif; ?>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ganti Gambar</label>
                <input type="file" name="note_image" accept="image/*"
                    class="block w-full text-sm text-slate-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-indigo-50 file:text-primary
                    hover:file:bg-indigo-100 transition cursor-pointer">
            </div>
            
            <div class="flex items-center justify-end gap-3 mt-4 pt-4 border-t border-gray-100">
                <a href="../index.php" 
                   class="px-5 py-2 rounded-lg text-gray-600 hover:bg-gray-100 transition text-sm font-medium">
                   Batal
                </a>
                <button type="submit" 
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-indigo-700 shadow-md transition text-sm font-medium">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</body>
</html>