<div class="main-content main-content-expanded">
    <?php if($this->session->flashdata('product_status')){
        $flash = $this->session->flashdata('product_status');
        echo '<div class="notification-toast '.($flash['type'] == 'success' ? 'success' : 'error').'">'.$flash['message'].'</div>'; 
        if($flash['type']=='success'){
          echo '<script>setTimeout(function(){document.querySelector(".notification-toast").style.display="none";},4000);</script>';
        } 
    } ?>

    <!-- Page Header -->
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;"><?php echo $title; ?></h1>
        <p style="color: #64748b;">Manage your license products and configurations</p>
    </div>

    <!-- Stats Grid -->
    <div class="dashboard-grid">
        <?php
        $active_products = 0;
        $total_versions = 0;
        $recent_products = 0;
        $estimated_revenue = 0;
        
        foreach($products as $product) {
            if($product['pd_status'] == 1) $active_products++;
            $versions = $this->products_model->get_latest_version($product['pd_pid']);
            if(!empty($versions)) $total_versions++;
            if(strtotime($product['pd_date_created']) > strtotime('-30 days')) $recent_products++;
            $estimated_revenue += 49.99; // Sample calculation
        }
        ?>
        
        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?php echo count($products); ?></div>
                    <div class="stat-label">Total Products</div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> <?php echo $recent_products; ?> this month
                    </div>
                </div>
                <div class="stat-icon primary">
                    <i class="fas fa-box"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?php echo $active_products; ?></div>
                    <div class="stat-label">Active Products</div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> <?php echo round(($active_products/max(count($products),1))*100); ?>% active
                    </div>
                </div>
                <div class="stat-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?php echo $total_versions; ?></div>
                    <div class="stat-label">With Versions</div>
                    <div class="stat-change neutral">
                        <i class="fas fa-code-branch"></i> Released
                    </div>
                </div>
                <div class="stat-icon warning">
                    <i class="fas fa-tags"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value">$<?php echo number_format($estimated_revenue, 1); ?>k</div>
                    <div class="stat-label">Est. Revenue</div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> Estimated
                    </div>
                </div>
                <div class="stat-icon danger">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div style="background: white; border-radius: 16px; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1); border: 1px solid #e2e8f0; overflow: hidden;">
        <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 1.125rem; font-weight: 600; color: #0f172a;">Product List</h2>
                <p style="font-size: 14px; color: #64748b; margin-top: 0.25rem;">Manage and monitor all your products</p>
            </div>
            <a href="<?php echo base_url('products/add'); ?>" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; border: none; padding: 0.625rem 1.25rem; border-radius: 10px; font-weight: 600; cursor: pointer; transition: transform 0.2s; text-decoration: none; display: inline-block;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <i class="fas fa-plus" style="margin-right: 0.5rem;"></i> Add Product
            </a>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%;">
                <thead style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <tr>
                        <th style="text-align: left; padding: 1rem 1.5rem; font-weight: 600; color: #475569; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em;">Product</th>
                        <th style="text-align: left; padding: 1rem 1.5rem; font-weight: 600; color: #475569; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em;">Product ID</th>
                        <th style="text-align: left; padding: 1rem 1.5rem; font-weight: 600; color: #475569; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em;">Version</th>
                        <th style="text-align: left; padding: 1rem 1.5rem; font-weight: 600; color: #475569; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em;">Status</th>
                        <th style="text-align: left; padding: 1rem 1.5rem; font-weight: 600; color: #475569; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em;">Created</th>
                        <th style="text-align: right; padding: 1rem 1.5rem; font-weight: 600; color: #475569; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <?php $latest_version = $this->products_model->get_latest_version($product['pd_pid']); ?>
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                                <td style="padding: 1rem 1.5rem;">
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                            <?php echo strtoupper(substr($product['pd_name'], 0, 2)); ?>
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: #0f172a;"><?php echo htmlspecialchars($product['pd_name']); ?></div>
                                            <div style="font-size: 13px; color: #64748b;">Product</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 1rem 1.5rem;">
                                    <code style="background: rgba(99, 102, 241, 0.1); color: #6366f1; padding: 0.25rem 0.5rem; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                        <?php echo htmlspecialchars($product['pd_pid']); ?>
                                    </code>
                                </td>
                                <td style="padding: 1rem 1.5rem;">
                                    <?php if(!empty($latest_version)): ?>
                                        <span style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 0.25rem 0.5rem; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                            v<?php echo htmlspecialchars($latest_version[0]['version']); ?>
                                        </span>
                                        <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                                            <?php echo date('M d, Y', strtotime($latest_version[0]['release_date'])); ?>
                                        </div>
                                    <?php else: ?>
                                        <span style="color: #64748b; font-size: 13px;">No versions</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 1rem 1.5rem;">
                                    <?php if ($product['pd_status'] == 1): ?>
                                        <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                            <span style="width: 8px; height: 8px; background: #10b981; border-radius: 50%;"></span>
                                            <span style="color: #10b981; font-weight: 600; font-size: 14px;">Active</span>
                                        </span>
                                    <?php else: ?>
                                        <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                            <span style="width: 8px; height: 8px; background: #ef4444; border-radius: 50%;"></span>
                                            <span style="color: #ef4444; font-weight: 600; font-size: 14px;">Inactive</span>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 1rem 1.5rem;">
                                    <div style="font-weight: 600; color: #0f172a;"><?php echo date('M d, Y', strtotime($product['pd_date_created'])); ?></div>
                                    <div style="font-size: 13px; color: #64748b;"><?php echo date('H:i', strtotime($product['pd_date_created'])); ?></div>
                                </td>
                                <td style="padding: 1rem 1.5rem; text-align: right;">
                                    <div style="display: inline-flex; gap: 0.5rem;">
                                        <?php $hidden = array('product' => $product['pd_pid']); echo form_open('/products/versions/add','', $hidden); ?>
                                            <button type="submit" style="padding: 0.5rem; background: rgba(99, 102, 241, 0.1); border: none; color: #6366f1; cursor: pointer; border-radius: 6px; transition: all 0.2s;" title="Add Version">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </form>
                                        <?php $hidden = array('product' => $product['pd_pid']); echo form_open('/products/versions/','', $hidden); ?>
                                            <button type="submit" style="padding: 0.5rem; background: rgba(16, 185, 129, 0.1); border: none; color: #10b981; cursor: pointer; border-radius: 6px; transition: all 0.2s;" title="Manage Versions">
                                                <i class="fas fa-code-branch"></i>
                                            </button>
                                        </form>
                                        <?php $hidden = array('product' => $product['pd_pid']); echo form_open('/products/edit','', $hidden); ?>
                                            <button type="submit" style="padding: 0.5rem; background: rgba(251, 146, 60, 0.1); border: none; color: #f97316; cursor: pointer; border-radius: 6px; transition: all 0.2s;" title="Edit Product">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </form>
                                        <?php 
                                        $hidden = array('product' => $product['pd_pid']);
                                        $js = 'id="delete_form_'.$product['pd_pid'].'"';
                                        echo form_open('/products/delete',$js, $hidden); ?>
                                            <button type="button" data-id="<?php echo $product['pd_pid']; ?>" data-title="product" data-body="Please note that all of the product <b><?php echo $product['pd_name']; ?></b>'s relevant records like (versions, licenses, activations and update download logs) will also be permanently deleted." class="button with-delete-confirmation" style="padding: 0.5rem; background: rgba(239, 68, 68, 0.1); border: none; color: #ef4444; cursor: pointer; border-radius: 6px; transition: all 0.2s;" title="Delete Product">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="padding: 3rem; text-align: center; color: #64748b;">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                                    <div style="width: 64px; height: 64px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-box" style="font-size: 24px; color: #94a3b8;"></i>
                                    </div>
                                    <div>
                                        <h3 style="font-weight: 600; color: #0f172a; margin-bottom: 0.5rem;">No products found</h3>
                                        <p style="color: #64748b;">Get started by creating your first product.</p>
                                    </div>
                                    <a href="<?php echo base_url('products/add'); ?>" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 10px; font-weight: 600; text-decoration: none; display: inline-block;">
                                        <i class="fas fa-plus" style="margin-right: 0.5rem;"></i> Add Your First Product
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

