
        <div class="element_video">
            <?php if (isset($v['video'])): ?>
                <?= $v['video'] ?>
                <div class="overlay"> <i class="fa fa-play-circle"></i></div>
                <div class="controls">
                    <button class="btn" id="play">
                        <i class="fa fa-play fa-2x"></i>
                    </button>
                    <button class="btn" id="stop">
                        <i class="fa fa-stop fa-2x"></i>
                    </button>
                    <div class="vol-controls">
                        <div id='vol-icon' class="vol-icon"><i class="fas fa-volume-up fa-2x"></i></div>
                        <div id="vol-range" class="vol__slider">
                            <input type="range" id="volume" class="player__slider" min="0" max="1" step="0.1" value="0.5">
                        </div>
                    </div>
                    <input type="range" id="progress" class="progress" min="0" max="100" step="0.1" value="0">
                    <select name="setvelocity" id='speed' class='speed'>
                        <option value="0.25">-0.25</option>
                        <option value="0.75">0.75</option>
                        <option value="1.0" selected>Normal</option>
                        <option value="1.25">1.25</option>
                        <option value="1.75">1.75</option>
                    </select>
                    <span class="timestamp" id="timestamp">00:00</span>
                    <!-- <span class="timestamp" id="timeend">00:00</span> -->
                    <button class='btn' id='fullscreen'>
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
                <div class="movil_controls">
                    <div class="first_line">
                        <input type="range" id="progress" class="progress" min="0" max="1" step="0.1" value="0">
                    </div>
                    <div class="second_line">
                        <button class="btn" id="play">
                            <i class="fa fa-play fa-2x"></i>
                        </button>
                        <button class="btn" id="stop">
                            <i class="fa fa-stop fa-2x"></i>
                        </button>
                        <div class="vol-controls">
                            <div id='vol-icon' class="vol-icon"><i class="fas fa-volume-up fa-2x"></i></div>
                            <div id="vol-range" class="vol__slider">
                                <input type="range" id="volume" class="player__slider" min="0" max="1" step="0.1" value="0.5">
                            </div>
                        </div>
                        <select name="setvelocity" id='speed' class='speed'>
                            <option value="0.25">-0.25</option>
                            <option value="0.75">0.75</option>
                            <option value="1.0" selected>Normal</option>
                            <option value="1.25">1.25</option>
                            <option value="1.75">1.75</option>
                        </select>
                        <span class="timestamp" id="timestamp">00:00</span>
                        <!-- <span class="timestamp" id="timeend">00:00</span> -->
                        <button class='btn' id='fullscreen'>
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
            <?php else: ?>
                <img width='100%;' height='100%;' src='<?= $v['no_video']; ?>' alt='<?= $v['titulo'] ?>' >
            <?php endif; ?>
        </div>