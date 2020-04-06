<?php
defined('BASEPATH') or exit('No direct script access allowed');
?><div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?php
            if (!empty($this->session->flashdata('errors'))) {
                echo '<div class="alert alert-danger fade in alert-dismissable" title="Error:"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>';
                echo $this->session->flashdata('errors');
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <div class="row page-header-buttons">
        <div class="col-md-12">
            <a href="<?= base_url('lead/' . $sub_base_path . $lead->id) ?>" class="btn btn-info btn-fill"><i class="fa fa-chevron-left" aria-hidden="true"></i>&nbsp; Back</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="header">
                    <h4 class="title">Leads / Clients Detail</h4>
                </div>
                <div class="content view">
                    <div class="row">
                        <div class="col-md-12">
                            #<?= (1600 + $lead->id); ?><br />
                            <?= $lead->firstname ?> <?= $lead->lastname ?><br />
                            <?= $lead->address ?><br />
                            <?= $lead->city ?>, <?= $lead->state ?><br />
                            C - <?= $lead->phone1 ?><br />
                            <?= $lead->email ?>
                        </div>
                    </div>
                </div>
                <div class="footer">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="<?= base_url('lead/' . $sub_base_path . $lead->id . '/photos'); ?>" class="btn btn-fill">Photos</a>
                            <a href="<?= base_url('lead/' . $sub_base_path . $lead->id . '/reports'); ?>" class="btn btn-fill">Photo Report</a>
                            <a href="<?= base_url('lead/' . $sub_base_path . $lead->id . '/docs'); ?>" class="btn btn-fill">Docs</a>
                            <a href="<?= base_url('lead/' . $sub_base_path . $lead->id . '/notes'); ?>" class="btn btn-fill">Notes</a>
                            <a href="<?= base_url('lead/' . $sub_base_path . $lead->id . '/public-folder'); ?>" class="btn btn-fill">Public Folder</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card lead-notes-card">
                <div class="header">
                    <h4 class="title">Leads / Clients Notes</h4>
                </div>
                <div class="content view">
                    <?php
                    if ($notes) {
                        foreach ($notes as $note) {
                            echo '<div class="row note-item">';
                            echo '<div class="col-md-12">';
                            echo '<label>' . $note->created_user_fullname . '</label>';
                            echo '<a href="' . base_url('lead/' . $sub_base_path . $lead->id . '/note/' . $note->id . '/delete') . '" data-method="POST" class="text-danger pull-right"><i class="fa fa-trash-o"></i></a></a>';
                            echo '<a href="#" data-noteid="' . $note->id . '" class="edit-note text-info pull-right"><i class="fa fa-pencil"></i></a></a>';
                            echo '<p>' . $note->note . '</p>';
                            echo '<small class="date-created">' . $note->created_at . '</small>';
                            echo '<div style="text-align: right;">';
                            echo '<small><a href="' . base_url('lead/' . $sub_base_path . $lead->id . '/note/' . $note->id . '/replies') . '">Thread Details</a></small>';
                            echo '</div>';
                            echo '</div>';
                            echo '<div id="note-item-edit-' . $note->id . '" class="note-item-edit col-md-12">';
                            echo '<form action="' . base_url('lead/' . $sub_base_path . $lead->id . '/note/' . $note->id . '/update') . '" method="post">';
                            echo '<div class="row">';
                            echo '<div class="col-md-12">';
                            echo '<div class="form-group">';
                            echo '<label>Your Note<span class="red-mark">*</span></label>';
                            echo '<textarea class="form-control note-input" name="note" placeholder="Your Note (You can use Ctrl + Enter for Submit)" rows="10" ctrl-enter-submit>' . str_replace('<br />', '', $note->note) . '</textarea>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '<div class="row">';
                            echo '<div class="col-md-12">';
                            echo '<div class="form-group">';
                            echo '<a href="#" data-noteid="' . $note->id . '" class="note-item-edit-cancel btn btn-info btn-fill">Cancel</a>';
                            echo '<button type="submit" class="btn btn-info btn-fill pull-right">Update</button>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</form>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>-</p>';
                    }
                    ?>
                </div>
            </div>
            <div class="card add-note-card">
                <div class="header">
                    <h4 class="title">Add Note</h4>
                </div>
                <div class="content view">
                    <div class="row add-note-form">
                        <div class="col-md-12">
                            <form action="<?= base_url('lead/' . $sub_base_path . $lead->id . '/add-note') ?>" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Your Note<span class="red-mark">*</span></label>
                                            <textarea class="form-control note-input" name="note" placeholder="Your Note (You can use Ctrl + Enter for Submit)" rows="10" ctrl-enter-submit></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-info btn-fill pull-right">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.note-item-edit-cancel, .edit-note').click(function(e) {
            e.preventDefault();
            var noteId = $(this).data('noteid');
            $('#note-item-edit-' + noteId).toggleClass('visible');
        });

        $('.note-input').atwho({
            at: '@',
            data: <?= json_encode($users) ?>,
            headerTpl: '<div class="atwho-header">User List:</div>',
            displayTpl: '<li>${name} (@${username})</li>',
            insertTpl: '${atwho-at}${username}',
            searchKey: 'username',
            limit: 100
        });
    });
</script>