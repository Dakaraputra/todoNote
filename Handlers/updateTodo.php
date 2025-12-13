<?php
require '../koneksi.php'; 

if (!$conn) {
    die("Koneksi gagal: " . pg_last_error());
}

$id = $_GET['id'] ?? $_POST['id'] ?? null;

// Jika tidak ada ID, kembalikan ke halaman utama 
if (!$id) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task = $_POST['task'];
    
    // Cek upload gambar
    if (!empty($_FILES['todo_image']['name'])) {

        $targetDir = "../uploads/"; 
        $fileName = time() . "_" . $_FILES['todo_image']['name'];
        
        $uploadPath = $targetDir . $fileName;
        $dbPath = "uploads/" . $fileName;

        if (move_uploaded_file($_FILES['todo_image']['tmp_name'], $uploadPath)) {
            $sql = "UPDATE todos SET task = $1, image_path = $2 WHERE id = $3";
            $result = pg_query_params($conn, $sql, [$task, $dbPath, $id]);
        }
    } else {
        $sql = "UPDATE todos SET task = $1 WHERE id = $2";
        $result = pg_query_params($conn, $sql, [$task, $id]);
    }

    if ($result) {
        // Sukses redirect ke index.php
        header("Location: ../index.php");
        exit;
    } else {
        echo "Error: " . pg_last_error($conn);
    }
}

$sql = "SELECT * FROM todos WHERE id = $1";
$result = pg_query_params($conn, $sql, [$id]);
$todo = pg_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Todo</title>
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

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-lg border border-gray-100">
        
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-slate-700">Update Todo</h2>
        </div>

        <form action="" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
            <input type="hidden" name="id" value="<?php echo $todo['id']; ?>">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Tugas</label>
                <input type="text" name="task" value="<?php echo htmlspecialchars($todo['task']); ?>" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition shadow-sm">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini</label>
                <?php if ($todo['image_path']): ?>
                    <div class="relative w-full h-48 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                        <img src="../<?php echo htmlspecialchars($todo['image_path']); ?>" 
                             alt="Current Image" 
                             class="w-full h-full object-cover">
                    </div>
                <?php else: ?>
                    <div class="w-full h-24 bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-400 text-sm">
                        Tidak ada gambar
                    </div>
                <?php endif; ?>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ganti Gambar</label>
                <input type="file" name="todo_image" accept="image/*"
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