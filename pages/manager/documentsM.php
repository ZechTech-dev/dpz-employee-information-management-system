<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/session.php';

sesh();

require_once __DIR__ . '/../../process/manager/documentsMProcess.php';
require_once __DIR__ . '/../../components/sideBar.php';
require_once __DIR__ . '/../../components/header.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Document Management</title>

    <link rel="stylesheet" href="/dpz-eims/assets/css/components/sideBar.css">
    <link rel="stylesheet" href="/dpz-eims/assets/css/components/header.css">
    <link rel="stylesheet" href="/dpz-eims/assets/css/components/global.css">
    <link rel="stylesheet" href="/dpz-eims/assets/css/manager/documentsM.css">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

    <main class="content">

        <div class="space"></div>

        <div class="list-header">

            <div class="left">

                <div class="cover">
                    <i class="bi bi-folder-fill"></i>
                </div>

                <div class="cover-info">

                    <h3>Document Management</h3>

                    <span>
                        View and manage employee documents
                    </span>

                </div>

            </div>

            <div class="employee-search">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    id="documentSearch"
                    placeholder="Search employee or document...">

            </div>

        </div>

        <section class="document-management">

            <div class="document-categories">

                <div class="categories-header">
                    Categories
                </div>

                <div class="categories">

                    <div
                        class="cat-item active"
                        data-category="all">

                        <span>All Documents</span>

                        <span class="count">
                            <?= $categoryCounts['all'] ?? 0 ?>
                        </span>

                    </div>

                    <div
                        class="cat-item"
                        data-category="Personal">

                        <span>Personal Documents</span>

                        <span class="count">
                            <?= $categoryCounts[2] ?? 0 ?>
                        </span>

                    </div>

                    <div
                        class="cat-item"
                        data-category="Employment">

                        <span>Employment Documents</span>

                        <span class="count">
                            <?= $categoryCounts[1] ?? 0 ?>
                        </span>

                    </div>

                    <div
                        class="cat-item"
                        data-category="Training">

                        <span>Training and Certificate</span>

                        <span class="count">
                            <?= $categoryCounts[3] ?? 0 ?>
                        </span>

                    </div>

                </div>

            </div>

            <section class="document-table-container">

                <table>

                    <thead>

                        <tr>
                            <th>Employee</th>
                            <th>Document Name</th>
                            <th>Category</th>
                            <th>Date Uploaded</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody id="documentTableBody">

                        <?php if (count($documents) > 0): ?>

                            <?php foreach ($documents as $document): ?>

                                <tr
                                    class="document-row"
                                    data-category="<?= htmlspecialchars($document['document_type_name']) ?>"
                                    data-name="<?= htmlspecialchars(strtolower($document['document_name'])) ?>"
                                    data-employee="<?= htmlspecialchars(strtolower($document['employee_name'])) ?>">

                                    <td>

                                        <div class="employee-info">

                                            <img
                                                src="/dpz-eims/uploads/profile/<?= htmlspecialchars($document['profile_picture'] ?: 'default-picture.jpg') ?>"
                                                alt="Profile"
                                                onerror="this.src='/dpz-eims/uploads/profile/default-picture.jpg'">

                                            <span>
                                                <?= htmlspecialchars($document['employee_name']) ?>
                                            </span>

                                        </div>

                                    </td>

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
                                            href="/dpz-eims/process/manager/documentsMProcess.php?action=view&id=<?= htmlspecialchars($document['document_id']) ?>"
                                            target="_blank"
                                            title="View document">

                                            <i class="bi bi-eye-fill"></i>

                                        </a>

                                        <a
                                            href="/dpz-eims/process/manager/documentsMProcess.php?action=download&id=<?= htmlspecialchars($document['document_id']) ?>"
                                            title="Download document">

                                            <i class="bi bi-download"></i>

                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td
                                    colspan="5"
                                    class="no-documents">

                                    No documents uploaded yet.

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </section>

        </section>

    </main>

</body>

</html>