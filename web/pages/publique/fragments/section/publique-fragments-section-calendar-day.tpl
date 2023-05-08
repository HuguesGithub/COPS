<!-- @version v1.23.05.07 -->
<section class="fc-timegrid fc-timeGridDay-view fc-view">
  <table role="grid" class="fc-scrollgrid table-bordered fc-scrollgrid-liquid" aria-describedby="Calendrier du jour">
    <tbody role="rowgroup">
      <!-- Entête avec le jour de la semaine -->
      <tr role="presentation" class="fc-scrollgrid-section fc-scrollgrid-section-header ">
        <th role="presentation">
          <div class="fc-scroller-harness">
            <div class="fc-scroller" style="overflow: hidden scroll;">
              <table role="presentation" class="fc-col-header ">
                <colgroup>
                  <col style="width: 55px;">
                </colgroup>
                <thead role="presentation">
                  <tr role="row">
                    <td aria-hidden="true" class="fc-timegrid-axis">
                      <div class="fc-timegrid-axis-frame"></div>
                    </td>
                    %1$s
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </th>
      </tr>
      <!-- Body contenant les événements "All-day" -->
      <tr role="presentation" class="fc-scrollgrid-section fc-scrollgrid-section-body ">
        <td role="presentation">
          <div class="fc-scroller-harness">
            <div class="fc-scroller" style="overflow: hidden scroll;">
              <div class="fc-daygrid-body fc-daygrid-body-unbalanced fc-daygrid-body-natural">
                <table role="presentation" class="fc-scrollgrid-sync-table">
                  <colgroup>
                    <col style="width: 55px;">
                  </colgroup>
                  <tbody role="presentation">
                    <tr role="row">
                      <td aria-hidden="true" class="fc-timegrid-axis fc-scrollgrid-shrink">
                        <div class="fc-timegrid-axis-frame fc-scrollgrid-shrink-frame fc-timegrid-axis-frame-liquid">
                          <span class="fc-timegrid-axis-cushion fc-scrollgrid-shrink-cushion fc-scrollgrid-sync-inner">all-day</span>
                        </div>
                      </td>
                      %2$s
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </td>
      </tr>

      <tr role="presentation" class="fc-scrollgrid-section">
        <td class="fc-timegrid-divider table-active"></td>
      </tr>

      <tr role="presentation" class="fc-scrollgrid-section fc-scrollgrid-section-body  fc-scrollgrid-section-liquid">
        <td role="presentation">
          <div class="fc-scroller-harness fc-scroller-harness-liquid">
            <div class="fc-scroller fc-scroller-liquid-absolute" style="overflow: hidden scroll;">
              <div class="fc-timegrid-body">
                <div class="fc-timegrid-slots">
                  <table aria-hidden="true" class="table-bordered">
                    <colgroup>
                      <col style="width: 55px;">
                    </colgroup>
                    <tbody>
                      %3$s
                    </tbody>
                  </table>
                </div>
                <div class="fc-timegrid-cols">
                  <table role="presentation">
                    <colgroup>
                      <col style="width: 55px;">
                    </colgroup>
                    <tbody role="presentation">
                      <tr role="row">
                        <td aria-hidden="true" class="fc-timegrid-col fc-timegrid-axis">
                          <div class="fc-timegrid-col-frame">
                            <div class="fc-timegrid-now-indicator-container"></div>
                          </div>
                        </td>
                        %4$s
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</section>
