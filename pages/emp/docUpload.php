    <?php

    require_once __DIR__ . '/../../config/db.php';
    require_once __DIR__ . '/../../config/session.php';

    sesh();

    require_once __DIR__ . '/../../process/emp/docUploadProcess.php';
    require_once __DIR__ . '/../../components/sideBar.php';
    require_once __DIR__ . '/../../components/header.php';
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Document Upload</title>

        <link rel="stylesheet" href="/dpz-eims/assets/css/components/sideBar.css">
        <link rel="stylesheet" href="/dpz-eims/assets/css/emp/docUpload.css">

        <link rel="stylesheet" href="/dpz-eims/assets/css/components/global.css">
        <link rel="stylesheet" href="/dpz-eims/assets/css/components/header.css">
        <!-- Bootstrap Icons -->
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    </head>


    <body>

        <main class="content">

            <div class="space"></div>
            <section class="docreq-head">
                <div class="head">

                    <div class="left-head">
                        <h1>Total of Uploaded Documents: <?php echo $documentTotal ?> </h1>
                    </div>

                    <div class="right-head">
                        <button type="button" id="uploadBtn">
                            <i class="bi bi-plus-circle-fill"></i>
                            Upload New Document
                        </button>

                    </div>
                </div>
            </section>

            <div class="docreq-body">

                <div class="left-body">

                    <div class="categories-header">
                        Categories
                    </div>

                    <div class="categories">

                        <!--cat - shorten term of category-->
                        <div class="cat-item active">All Documents

                            <span class="count">
                                <?= $categoryCounts['all'] ?? 0 ?>
                            </span>

                        </div>
                        <div class="cat-item">Personal Documents

                            <span class="count">
                                <?= $categoryCounts[2] ?? 0 ?>
                            </span>

                        </div>
                        <div class="cat-item">Employment Documents

                            <span class="count">
                                <?= $categoryCounts[1] ?? 0 ?>
                            </span>

                        </div>
                        <div class="cat-item">Training and Certificate

                            <span class="count">
                                <?= $categoryCounts[3] ?? 0 ?>
                            </span>

                        </div>
                    </div>

                    <div class="note">
                        <span>----- NOTE ------</span>
                    </div>
                    <div class="supported-files">
                        <i class="bi bi-info-circle"></i>

                        <div>
                            <strong>Supported files</strong>
                            <span>PNG, JPG, PDF</span>
                        </div>
                    </div>

                </div>


                <section class="document-table-container">
                    <div class="right-body">
                        <table>
                            <thead>
                                <tr>
                                    <th>
                                        Document Name
                                    </th>

                                    <th>
                                        Category
                                    </th>

                                    <th>
                                        Date Uploaded
                                    </th>

                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>

                            <tbody id="documentTableBody">

                                <?php if (count($documents) > 0): ?>

                                    <?php foreach ($documents as $document): ?>

                                        <tr class="document-row">

                                            <td class="document-name">

                                                <i class="bi bi-file-earmark-text"></i>

                                                <span>
                                                    <?= htmlspecialchars($document['document_name']) ?>
                                                </span>

                                            </td>

                                            <td>
                                                <?= htmlspecialchars($document['document_type_name']) ?>
                                            </td>

                                            <td>
                                                <?= date(
                                                    "F j, Y",
                                                    strtotime($document['uploaded_at'])
                                                ) ?>
                                            </td>

                                            <td class="actions">

                                                <a
                                                    href="/dpz-eims/process/emp/viewDocument.php?id=<?= $document['document_id'] ?>"
                                                    target="_blank"
                                                    title="View document">

                                                    <i class="bi bi-eye-fill"></i>

                                                </a>

                                                <a
                                                    href="/dpz-eims/process/emp/downloadDocument.php?id=<?= $document['document_id'] ?>"
                                                    title="Download document">

                                                    <i class="bi bi-download"></i>

                                                </a>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>

                                        <td colspan="4" class="no-documents">
                                            No documents uploaded yet.
                                        </td>

                                    </tr>

                                <?php endif; ?>

                            </tbody>


                        </table>
                    </div>
                </section>
            </div>

            <div class="upload-modal" id="uploadModal">

                <div class="upload-box">

                    <div class="upload-header">

                        <h2>Upload New Document</h2>

                        <button type="button" id="closeUpload">
                            &times;
                        </button>

                    </div>


                    <div class="upload-content">

                        <!-- LEFT SIDE -->
                        <div class="upload-left">

                            <form
                                id="uploadForm"
                                action="/dpz-eims/process/emp/docUploadProcess.php"
                                method="POST"
                                enctype="multipart/form-data">

                                <label for="documentType">
                                    Document Category
                                </label>

                                <select
                                    name="document_type_id"
                                    id="documentType"
                                    required>

                                    <option value="">
                                        Select Category
                                    </option>

                                    <?php while ($type = $documentTypes->fetch_assoc()): ?>

                                        <option
                                            value="<?= $type['document_type_id'] ?>">

                                            <?= htmlspecialchars($type['document_type_name']) ?>

                                        </option>

                                    <?php endwhile; ?>

                                </select>


                                <label for="documentFile">
                                    Select Document
                                </label>

                                <input
                                    type="file"
                                    name="document"
                                    id="documentFile"
                                    accept=".png,.jpg,.jpeg,.pdf"
                                    required>


                                <p id="fileName">
                                    No file selected
                                </p>


                                <small>
                                    Allowed files: PNG, JPG, JPEG, PDF
                                </small>


                                <div class="upload-buttons">

                                    <button
                                        type="button"
                                        id="cancelUpload">

                                        Cancel

                                    </button>

                                    <button type="submit">

                                        Upload Document

                                    </button>

                                </div>

                            </form>

                        </div>


                        <!-- RIGHT SIDE -->
                        <div class="upload-right">

                            <div class="preview-header">

                                <i class="bi bi-eye-fill"></i>

                                <span>Document Preview</span>

                            </div>


                            <div
                                class="file-preview"
                                id="filePreview">

                                <div class="no-preview">

                                    <i class="bi bi-eye-fill"></i>

                                    <p>No document selected</p>

                                    <span>
                                        Select a PDF or image to preview it here.
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </main>

        <script src="/dpz-eims/assets/js/emp/docUploads.js"></script>

    </body>

    </html>